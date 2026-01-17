<?php

namespace App\Services\SoalImport;

use ZipArchive;
use App\Models\ImportSession;
use App\Models\ImportQuestion;
use App\Models\ImportAnswer;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportParserService
{
    /**
     * ENTRY POINT (dipanggil controller)
     */
    public function handle($zipFile, $examId, $userId)
    {
        /**
         * 1. BUAT SESSION
         */
        $session = ImportSession::create([
            'user_id' => $userId,
            'exam_id' => $examId,
            'status' => 'draft',
            'original_file' => $zipFile->getClientOriginalName(),
        ]);

        /**
         * 2. EXTRACT ZIP
         */
        $extractPath = storage_path("app/imports/{$session->id}");

        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0777, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipFile->getRealPath()) !== true) {
            throw new \Exception('ZIP tidak bisa dibuka');
        }

        $zip->extractTo($extractPath);
        $zip->close();

        /**
         * 3. AMBIL EXCEL
         */
        $excelPath = collect(glob($extractPath.'/*.xlsx'))->first();
        if (!$excelPath) {
            throw new \Exception('Excel tidak ditemukan');
        }

        $spreadsheet = IOFactory::load($excelPath);

        /**
         * 4. PARSE QUESTIONS
         */
        $questionSheet = $spreadsheet->getSheetByName('questions');
        $questionRows  = $questionSheet->toArray(null, true, true, true);
        array_shift($questionRows);

        foreach ($questionRows as $row) {

            $questionImage = $this->resolveImage(
                $extractPath,
                $row['C'] ?? null,
                $session->id
            );

            $question = ImportQuestion::create([
                'import_session_id' => $session->id,
                'question_text' => trim($row['B']),
                'question_image' => $questionImage,
                'score' => (int) ($row['D'] ?? 1),
                'type' => 'PG',
                'order_no' => (int) $row['A'],
            ]);

            $this->insertAnswers(
                $spreadsheet,
                $question,
                $extractPath,
                $session->id
            );
        }

        return $session;
    }

    /**
     * PARSE ANSWERS
     */
    protected function insertAnswers($spreadsheet, $question, $extractPath, $sessionId)
    {
        $sheet = $spreadsheet->getSheetByName('answers');
        $rows  = $sheet->toArray(null, true, true, true);
        array_shift($rows);

        // dd([
        //     'extractPath' => $extractPath,
        //     'question_order' => $question->order_no,
        //     'first_answer_row' => $rows[0],
        // ]);

        foreach ($rows as $row) {

            if ((int) $row['A'] !== $question->order_no) {
                continue;
            }

            $answerImage = $this->resolveImage(
                $extractPath,
                $row['D'] ?? null,
                $sessionId
            );

            ImportAnswer::create([
                'import_question_id' => $question->id,
                'option_key' => trim($row['B']),
                'answer_text' => trim($row['C']),
                'answer_image' => $answerImage,
                'is_true' => strtoupper(trim($row['E'])) === 'TRUE',
            ]);
        }
    }

    /**
     * AMBIL GAMBAR DARI FOLDER images/
     */
    protected function resolveImage($extractPath, $filename, $sessionId)
    {
        if (!$filename) return null;

        $filename = trim($filename);

        if (!str_contains($filename, '.')) {
            $filename .= '.png';
        }

        $possiblePath = $extractPath.'/images/'.$filename;

        if (!file_exists($possiblePath)) {
            return null;
        }

        $targetPath = "imports/{$sessionId}/".$filename;

        Storage::disk('public')->put(
            $targetPath,
            file_get_contents($possiblePath)
        );

        return $targetPath;
    }

}
