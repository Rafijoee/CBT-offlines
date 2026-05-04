<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserExam; // Pastikan ini sesuai dengan model Pivot/tabel relasi ujianmu

class CheckExamStatus
{

    public function handle(Request $request, Closure $next)
    {
        // Sesuaikan 'exams' dengan nama parameter di Route::get('/exam/show/{exams}/...')
        $examId = $request->route('exams');

        if ($examId) {
            $status = UserExam::where('user_id', Auth::id())
                ->where('exam_id', $examId)
                ->first();

            // Pastikan parameter 'id' disertakan
            if ($status && $status->status === 'is_blocked') {
                return redirect()->route('exam.blocked', ['id' => $examId]);
            }
        }

        return $next($request);
    }
}