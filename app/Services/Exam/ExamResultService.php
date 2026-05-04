<?php

namespace App\Services\Exam;

use App\Models\UserExam;

class ExamResultService
{
    public function calculate(UserExam $userExam)
    {
        if ($userExam->status == 'finished') {
        
        }
        $userExam->load([
            'exam.bankSoals.answers',
            'userAnswers'
        ]);

        $benar = 0;
        $salah = 0;
        $kosong = 0;

        foreach ($userExam->exam->bankSoals as $soal) {

            $correctAnswer = $soal->answers->where('true', 1)->first();

            $studentAnswer = $userExam->userAnswers
                ->where('bank_soal_id', $soal->id)
                ->first();

            if (!$studentAnswer || !$studentAnswer->answer_id) {
                $kosong++;
            } elseif ($studentAnswer->answer_id == $correctAnswer?->id) {
                $benar++;
            } else {
                $salah++;
            }
        }

        $total = $userExam->exam->bankSoals->count();
    
        $nilai = $total > 0
            ? round(($benar / $total) * 100, 2)
            : 0;

        return [
            'benar'  => $benar,
            'salah'  => $salah,
            'kosong' => $kosong,
            'total'  => $total,
            'nilai'  => $nilai
        ];
    }
}