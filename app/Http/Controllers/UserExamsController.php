<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\Exams;
use App\Models\UserAnswer;
use App\Models\Answer;
use App\Models\UserExam;
use Illuminate\Http\Request;
use App\Services\Exam\ExamResultService;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;


class UserExamsController extends Controller
{
public function reportViolation(Request $request)
{
    $request->validate([
        'user_exam_id' => 'required|exists:user_exams,id',
        'status'       => 'required|string',
        'reason'       => 'required|string'
    ]);

    $userExam = UserExam::findOrFail($request->user_exam_id);
    $userExam->update([
        'status' => $request->status,
        // Optional: simpan alasan di kolom catatan jika ada
    ]);

    return response()->json(['success' => true]);
}
    public function masuk(Exams $exams)
    {   
        $userId = auth()->id();

        // ❌ kalau diblokir
        if (UserExam::where('user_id', $userId)
            ->where('exam_id', $exams->id)
            ->where('status', 'is_blocked')
            ->exists()) {

            return redirect()->route('exam.blocked', ['id' => $exams->id]);
        }

        // ambil / buat data
        $userExam = UserExam::firstOrCreate(
            [
                'user_id' => $userId,
                'exam_id' => $exams->id,
            ],
            [
                'status' => 'ongoing'
            ]
        );

        // ✅ kalau sudah selesai
        if ($userExam->status === 'finished') {
            return redirect()->route('exam.hasil', $userExam->id);
        }

        // ✅ SET started_at HANYA SEKALI (INI KUNCI TIMER)
        if (!$userExam->started_at) {
            $userExam->update([
                'started_at' => now()
            ]);
        }

        // redirect ke soal pertama
        return redirect()->route('exam.show', [
            'exams' => $exams->id,
            'bankSoal' => null
        ]);
    }
    public function hasil(UserExam $userExam, ExamResultService $service)
    {
        $result = $service->calculate($userExam);
        if ($result['nilai'] >= 70) {
            $hasil = 'lulus';
        } else {
            $hasil = 'tidak lulus';
        }
        $userExam->update([
            'hasil' => $hasil
        ]);
        
        return view('exams.hasil', compact('userExam', 'result', 'hasil'));
    }
public function edit(
    Exams $exams,
    BankSoal $bankSoal = null,
    ExamResultService $service
) {
    $userId = auth()->id();

    $userExam = UserExam::where('user_id', $userId)
        ->where('exam_id', $exams->id)
        ->firstOrFail();

    // =====================
    // BLOCKED
    // =====================

    if ($userExam->status === 'is_blocked') {
        return redirect()->route('exam.blocked', $exams->id);
    }

    // =====================
    // SUDAH FINISH
    // =====================
    if ($userExam->status === 'finished') {
        return redirect()->route('exam.hasil', $userExam->id);
    }

    // =====================
    // SET STARTED AT
    // =====================
    if (!$userExam->started_at) {
        $userExam->update([
            'started_at' => now()
        ]);
    }

    // =====================
    // VALIDASI WAKTU HABIS
    // =====================
    $endTime = $userExam->started_at
        ->copy()
        ->addMinutes($exams->time);

    if (now()->greaterThanOrEqualTo($endTime)) {

        // hitung nilai
        $result = $service->calculate($userExam);

        // update hasil
        $userExam->update([
            'skor' => $result['nilai'],
            'status' => 'finished',
        ]);

        return redirect()->route('exam.hasil', $userExam->id);
    }

    // =====================
    // AMBIL SOAL
    // =====================
    $soals = BankSoal::where('exams_id', $exams->id)
        ->orderBy('id')
        ->get();

    if ($soals->isEmpty()) {
        abort(404, 'Soal tidak ditemukan');
    }

    $soals = $soals->shuffle($userExam->id);

    // default soal pertama
    if (!$bankSoal || !$bankSoal->exists) {
    $bankSoal = $soals->first();
    }

    $question = $bankSoal;

    $answers = $question->answers()->get();

    $banyakSoal = $soals->count();

    // =====================
    // JAWABAN USER
    // =====================
    $userAnswer = UserAnswer::where('user_exam_id', $userExam->id)
        ->where('bank_soal_id', $question->id)
        ->first();

    $userAnswersAll = UserAnswer::where('user_exam_id', $userExam->id)
        ->get()
        ->keyBy('bank_soal_id');

    // =====================
    // VIEW
    // =====================
    return view('exams.create', compact(
        'exams',
        'question',
        'answers',
        'banyakSoal',
        'userAnswer',
        'userAnswersAll',
        'soals',
        'userExam'
    ));
}
public function save(Request $request)
{
    $request->validate([
        'user_exam_id' => 'required|exists:user_exams,id',
        'bank_soal_id' => 'required|exists:bank_soals,id',
        'answer_id'    => 'nullable|exists:answers,id',
        'ragu'         => 'required|boolean'
    ]);
    UserAnswer::updateOrCreate(
        [
            'user_exam_id' => $request->user_exam_id,
            'bank_soal_id' => $request->bank_soal_id
        ],
        [
            'user_id'   => auth()->id(),
            'answer_id' => $request->answer_id,
            'ragu'      => $request->ragu
        ]
    );

    return response()->json(['success' => true]);
}


public function finish(UserExam $userExam, ExamResultService $service)
{
$endTime = $userExam->started_at->addMinutes($userExam->exam->time);
if (now()->greaterThan($endTime)) {
    // tetap allow finish tapi tandai waktu habis
    $userExam->update(['is_late' => true]);
}
    $result = $service->calculate($userExam);

    $userExam->update([
        'skor' => $result['nilai'],
        'status' => 'finished'
    ]);

    return redirect()->route('exam.hasil', $userExam->id);
}
public function downloadExam($id)
{
    $exam = Exams::with('bankSoals.answers')->find($id);


    $zip = new ZipArchive;
    $fileName = 'exam_'.$exam->mapel.'_'.$exam->id.'.zip';

    $zip->open(storage_path($fileName), ZipArchive::CREATE);

    // 1. masukkan JSON
    $data = [
        'exam' => $exam,
        'questions' => $exam->bankSoals
            ->map(function ($q) {
                return [
                    'question_text' => $q->question_text,
                    'gambar' => $q->gambar,
                    'answers' => $q->answers->map(function ($a) {
                        return [
                            'text' => $a->text,
                            'gambar' => $a->gambar,
                            'true' => $a->true
                        ];
                    })
                ];
            })
            ->toArray()
    ];

    $zip->addFromString('exam.json', json_encode($data));

    // 2. masukkan gambar
// 2. masukkan gambar
    foreach ($exam->bankSoals as $q) {

        // 1. Gambar Soal
        if ($q->gambar) {
            // Gabungkan: public_path + storage + path dari DB
            $cleanPath = ltrim($q->gambar, '/');
            $fullPath = public_path('storage/' . $cleanPath);

            if (file_exists($fullPath)) {
                // Parameter kedua adalah letak file di dalam ZIP
                // Kita gunakan $cleanPath agar saat di-extract nanti, 
                // folder imports/69/ tetap terbentuk otomatis
                $zip->addFile($fullPath, $cleanPath);
            }
        }

        // 2. Gambar Jawaban
        foreach ($q->answers as $a) {
            if ($a->gambar) {
                $cleanPathAns = ltrim($a->gambar, '/');
                $fullPathAns = public_path('storage/' . $cleanPathAns);

                if (file_exists($fullPathAns)) {
                    $zip->addFile($fullPathAns, $cleanPathAns);
                }
            }
        }
    }
    

    $zip->close();

    return response()->download(storage_path($fileName));
}

public function importZip(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:zip'
    ]);

    $file = $request->file('file');

    $extractPath = storage_path('app/temp/'.time());
    mkdir($extractPath, 0777, true);

    $zip = new \ZipArchive;
    $zip->open($file->getRealPath());
    $zip->extractTo($extractPath);
    $zip->close();

    // ambil JSON
    $json = file_get_contents($extractPath.'/exam.json');
    $data = json_decode($json, true);


    // simpan exam
    $exam = Exams::create([
        'mapel' => $data['exam']['mapel'],
        'soal' => $data['exam']['soal'],
        'time' => $data['exam']['time'],
        'opened_time' => $data['exam']['opened_time'],
        'closed_time' => $data['exam']['closed_time'],
        'kelas' => $data['exam']['kelas'],
        'token' => $data['exam']['token'],
        'offline_mode' => 1
    ]);

    // simpan soal
    foreach ($data['questions'] as $q) {

        // 1. simpan soal
        $soal = BankSoal::create([
            'exams_id' => $exam->id,
            'question_text' => $q['question_text'],
            'gambar' => $q['gambar'] ?? null
        ]);

        // 2. simpan jawaban 
        if (isset($q['answers'])) {
            foreach ($q['answers'] as $a) {
                Answer::create([
                    'bank_soal_id' => $soal->id,
                    'text' => $a['text'],
                    'gambar' => $a['gambar'] ?? null,
                    'true' => $a['true'] ?? 0
                ]);
            }
        }
    }
    

    // copy gambar
    $this->copyImages($extractPath);

    return back()->with('success', 'Import berhasil');
}

private function copyImages($sourcePath)
{
    // 1. Pastikan destination mengarah ke folder storage di dalam public
    $destination = public_path('storage');

    $files = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($sourcePath)
    );

    foreach ($files as $file) {
        if ($file->isFile()) {
            $filePath = $file->getRealPath();

            if (preg_match('/\.(jpg|jpeg|png)$/i', $filePath)) {
                
                // 2. Cara paling aman mendapatkan relative path di Windows:
                // Kita ambil bagian setelah folder 'temp\nomor_timestamp\'
                $tempPathPart = 'temp' . DIRECTORY_SEPARATOR;
                $afterTemp = explode($tempPathPart, $filePath);
                
                if (isset($afterTemp[1])) {
                    // Ambil bagian setelah timestamp (misal: '70\soal1_a.png')
                    // Kita pecah lagi berdasarkan separator untuk membuang timestamp
                    $parts = explode(DIRECTORY_SEPARATOR, $afterTemp[1]);
                    array_shift($parts); // Hapus folder timestamp (misal: 1776108735)
                    
                    $relativePath = implode(DIRECTORY_SEPARATOR, $parts);
                } else {
                    // Jika gagal, fallback ke nama file saja
                    $relativePath = $file->getFilename();
                }

                // 3. Gabungkan menjadi target path yang benar
                $targetPath = $destination . DIRECTORY_SEPARATOR . $relativePath;
                $directory = dirname($targetPath);

                // Buat folder jika belum ada
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                copy($filePath, $targetPath);
            }
        }
    }
}

}