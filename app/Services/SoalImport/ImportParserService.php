<?php

namespace App\Services\SoalImport;

use ZipArchive;
use App\Models\ImportAnswer;
use App\Models\ImportSession;
use App\Models\ImportQuestion;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportParserService
{
    public function handle($zipFile, $examId, $userId)
    {
        // 1. Import session
        $session = ImportSession::create([
            'user_id' => $userId,
            'exam_id' => $examId,
            'status' => 'draft',
            'original_file' => $zipFile->getClientOriginalName(),
        ]);

        // 2. Extract ZIP (sementara, NON public)
        $extractPath = storage_path("app/imports/{$session->id}");
        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0777, true);
        }

        $zip = new ZipArchive;
        $zip->open($zipFile->getRealPath());
        $zip->extractTo($extractPath);
        $zip->close();

        // 3. Excel
        $excelPath = collect(glob($extractPath.'/*.xlsx'))->first();
        if (!$excelPath) {
            throw new \Exception('File Excel tidak ditemukan.');
        }

        $spreadsheet = IOFactory::load($excelPath);
        $questionSheet = $spreadsheet->getSheetByName('questions');
        $rows = $questionSheet->toArray();
        unset($rows[0]); // header

        foreach ($rows as $index => $row) {

            // === PINDAHKAN GAMBAR SOAL KE PUBLIC ===
            $questionImagePath = null;
            if (!empty($row[2])) {
                $questionImagePath = $this->moveToPublic(
                    $extractPath.'/'.$row[2],
                    "imports/{$session->id}"
                );
            }

            $question = ImportQuestion::create([
                'import_session_id' => $session->id,
                'question_text' => $row[1],
                'question_image' => $questionImagePath,
                'score' => $row[3] ?? 1,
                'type' => $row[4] ?? 'PG',
                'order_no' => $index,
            ]);

            if ($question->type === 'PG') {
                $this->insertAnswers($spreadsheet, $question, $extractPath, $session->id);
            }
        }

        return $session;
    }

    protected function insertAnswers($spreadsheet, $question, $extractPath, $sessionId)
    {
        $sheet = $spreadsheet->getSheetByName('answers');
        $rows = $sheet->toArray();
        unset($rows[0]);

        foreach ($rows as $row) {
            if ($row[0] == $question->order_no) {

                // === PINDAHKAN GAMBAR JAWABAN ===
                $answerImagePath = null;
                if (!empty($row[3])) {
                    $answerImagePath = $this->moveToPublic(
                        $extractPath.'/'.$row[3],
                        "imports/{$sessionId}"
                    );
                }

                ImportAnswer::create([
                    'import_question_id' => $question->id,
                    'option_key' => $row[1],
                    'answer_text' => $row[2],
                    'answer_image' => $answerImagePath,
                    'is_true' => strtoupper($row[4]) === 'TRUE',
                ]);
            }
        }
    }

    protected function moveToPublic($sourcePath, $publicDir)
    {
        if (!file_exists($sourcePath)) {
            return null;
        }

        $filename = basename($sourcePath);
        $targetPath = $publicDir.'/'.$filename;

        Storage::disk('public')->put(
            $targetPath,
            file_get_contents($sourcePath)
        );

        return $targetPath; // SIMPAN RELATIVE PATH
    }
}
