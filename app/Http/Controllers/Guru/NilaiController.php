<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\UserExam;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
    $data = UserExam::with(['user', 'exam'])
        ->when($request->kelas, function ($query) use ($request) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        })
        ->when($request->mapel, function ($query) use ($request) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('mapel', $request->mapel);
            });
        })
        ->when($request->search, function ($query) use ($request) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // ambil data unik untuk dropdown
    $mapels = \App\Models\Exams::select('mapel')
                ->distinct()
                ->pluck('mapel');

    return view('nilai-guru.index', compact('data', 'mapels'));
    }

    public function detail($userExamId)
    {
        $userExam = UserExam::with([
            'exam.bankSoals.answers',
            'userAnswers'
        ])->findOrFail($userExamId);
        $user = $userExam->user;
        return view('nilai-guru.detail', compact('userExam', 'user'));
    }
public function downloadResult(Request $request)
{
    $type = $request->type;

    $query = UserExam::with(['user', 'exam']);

    // FILTER MAPEL
    if ($request->filled('mapel')) {
        $query->whereHas('exam', function ($q) use ($request) {
            $q->where('mapel', $request->mapel);
        });
    }

    // FILTER SEARCH NAMA
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    // FILTER KELAS
    if ($request->filled('kelas')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('kelas', $request->kelas);
        });
    }

    $results = $query->get()->map(function ($item) {

        $nilai = $item->skor;

        return [
            'nama_siswa' => $item->user->name,
            'mapel' => $item->exam->mapel,
            'nilai' => $nilai,
            'keterangan' => $nilai >= 78
                ? 'Lulus'
                : 'Tidak Lulus',
        ];
    });

    // ================= PDF =================
    if ($type === 'pdf') {

        $pdf = Pdf::loadView('pdf.exam-result', [
            'results' => $results
        ]);

        return $pdf->download('hasil-ujian.pdf');
    }

    // ================= CSV =================
    if ($type === 'csv') {

        $response = new StreamedResponse(function () use ($results) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Nama Siswa',
                'Mata Pelajaran',
                'Nilai',
                'Keterangan'
            ]);

            foreach ($results as $result) {

                fputcsv($handle, [
                    $result['nama_siswa'],
                    $result['mapel'],
                    $result['nilai'],
                    $result['keterangan'],
                ]);
            }

            fclose($handle);
        });

        $response->headers->set(
            'Content-Type',
            'text/csv'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="hasil-ujian.csv"'
        );

        return $response;
    }

    return back()->with('error', 'Format tidak valid');
}
    public function syncUserExam(Request $request)
    {
        $request->validate([
            'user_exams' => 'required|array'
        ]);

        foreach ($request->user_exams as $data) {

            $exam = Exam::where('uuid', $data['exam_uuid'])->first();

            if (!$exam) {
                continue;
            }

            UserExam::updateOrCreate(
                [
                    'user_id' => $data['user_id'],
                    'exam_id' => $exam->id,
                ],
                [
                    'score' => $data['score'],
                    'status' => $data['status'],
                    'started_at' => $data['started_at'],
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json([
            'success' => true
        ]);
    }
public function syncToServer()
{
    $userExams = UserExam::where('is_synced', false)->get();

    if ($userExams->isEmpty()) {
        return back()->with('success', 'Tidak ada data untuk disinkron.');
    }

    $payload = [];

    foreach ($userExams as $item) {

        $payload[] = [
            'user_id' => $item->user_id,
            'exam_uuid' => $item->exam_uuid,
            'score' => $item->score,
            'status' => $item->status,
            'started_at' => $item->started_at,
        ];
    }

    $response = Http::post(
        'https://domain-server-pusat.com/api/sync/user-exam',
        [
            'user_exams' => $payload
        ]
    );

    if ($response->successful()) {

        foreach ($userExams as $item) {

            $item->update([
                'is_synced' => true,
                'synced_at' => now(),
            ]);
        }

        return back()->with('success', 'Berhasil sinkronisasi.');
    }

    return back()->with('error', 'Sinkronisasi gagal.');
}
}
