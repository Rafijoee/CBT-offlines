<?php
class ImportValidationService
{
    public function validateSession(ImportSession $session)
    {
        foreach ($session->questions as $question) {
            if ($question->type === 'PG') {
                if ($question->answers->where('is_true', true)->count() !== 1) {
                    throw new \Exception(
                        "Soal {$question->order_no} harus punya 1 jawaban benar"
                    );
                }
            }
        }
    }
}
