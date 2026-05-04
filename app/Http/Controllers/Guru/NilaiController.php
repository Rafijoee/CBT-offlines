<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\UserExam;
use Illuminate\Http\Request;

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
}
