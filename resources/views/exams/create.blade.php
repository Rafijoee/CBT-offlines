@extends('layouts.exam')


@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="space-y-6">
    
    {{-- HEADER --}}
    <div class="flex justify-between items-center rounded-2xl p-6 text-white
    bg-gradient-to-r from-blue-500 to-purple-500 shadow-lg">
    
    <div>
        <h1 class="text-3xl font-bold">{{ $exams->mapel }}</h1>
            <p>⏱ Sisa waktu: <span id="timer">--:--</span></p>
        </div>

        <div class="flex gap-3">
            <button id="markBtn"
                class="bg-yellow-400 px-4 py-2 rounded-xl font-semibold text-black">
                ⚑ Tandai Ragu
            </button>

            <form id= "finishForm" action="{{ route('exam.finish', $userExam->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-500 px-4 py-2 rounded-xl font-semibold text-white hover:bg-red-600">
                    🏁 Selesai Ujian
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- SOAL --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6 space-y-6">

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl">
                {!! $question->question_text !!}
            </div>

            <form id="answerForm">
                @csrf
                <input type="hidden" name="user_exam_id" value="{{ $userExam->id }}">
                <input type="hidden" name="bank_soal_id" value="{{ $question->id }}">
                <input type="hidden" name="ragu" id="raguInput"
                    value="{{ $userAnswer?->ragu ?? 0 }}">

                <div class="space-y-4 mt-4">
                    @foreach($answers as $opsi)

                        @php
                            $isSelected = $userAnswer && $userAnswer->answer_id == $opsi->id;
                        @endphp

                        <label
                            class="answer-option block border rounded-xl p-4 cursor-pointer transition-all duration-200
                            {{ $isSelected ? 'border-blue-500 bg-blue-100 ring-2 ring-blue-300' : 'hover:bg-blue-50' }}">

                            <input type="radio"
                                class="answer-radio hidden"
                                name="answer_id"
                                value="{{ $opsi->id }}"
                                {{ $isSelected ? 'checked' : '' }}>

                            <span class="font-medium">
                                {{ $opsi->text }}
                            </span>

                        </label>

                    @endforeach
                </div>
            </form>

            {{-- NAVIGASI --}}
            <div class="flex justify-between pt-6">

                @php
                    $currentIndex = $soals->search(fn($s) => $s->id == $question->id);
                    $isLast = $currentIndex == $banyakSoal - 1;
                @endphp

                @if($currentIndex > 0)
                    <button class="prevBtn px-4 py-2 bg-gray-200 rounded-xl"
                        data-id="{{ $soals[$currentIndex - 1]->id }}">
                        ← Sebelumnya
                    </button>
                @else
                    <div></div>
                @endif

                @if(!$isLast)
                    <button class="nextBtn px-4 py-2 bg-blue-500 text-white rounded-xl"
                        data-id="{{ $soals[$currentIndex + 1]->id }}">
                        Selanjutnya →
                    </button>
                @else
                <form action="{{ route('exam.finish', $userExam->id) }}" method="POST">
                @csrf
                
                <button type="submit"
                    class="bg-red-500 px-4 py-2 rounded-xl font-semibold text-white hover:bg-red-600">
                    🏁 Selesai Ujian
                </button>
                </form>
                @endif

            </div>
        </div>

        {{-- DAFTAR SOAL --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <h3 class="font-bold text-lg mb-4">Daftar Soal</h3>

            <div class="grid grid-cols-5 gap-2">
                @foreach($soals as $index => $s)

                    @php
                        $jawaban = $userAnswersAll[$s->id] ?? null;

                        if ($jawaban && $jawaban->ragu) {
                            $color = 'bg-yellow-400';
                        } elseif ($jawaban && $jawaban->answer_id) {
                            $color = 'bg-green-500 text-white';
                        } else {
                            $color = 'bg-gray-200';
                        }
                    @endphp

                    <button type="button"
                        id="btn-{{ $s->id }}"
                        class="number-btn rounded-lg py-2 text-sm text-center {{ $color }}"
                        data-id="{{ $s->id }}">
                        {{ $index + 1 }}
                    </button>

                @endforeach
            </div>

        </div>

    </div>
</div>

<script>
/**
 * =========================
 * 1. GLOBAL INIT
 * =========================
 */
let isFinishing = false;
const duration = {{ $exams->time }};
const startedAt = new Date("{{ optional($userExam->started_at)->toIso8601String() ?? now()->toIso8601String() }}").getTime();
const endTime = startedAt + (duration * 60 * 1000);

const userExamId = "{{ $userExam->id }}";
const examId = "{{ $exams->id }}";
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let isExamActive = true;
let wasFullscreen = false;
let isNavigating = false; // 🔥 penting (fix bug pindah soal)

/**
 * =========================
 * 2. ANTI CHEAT
 * =========================
 */
function kickUser(reason = "Pelanggaran sistem") {
    if (!isExamActive) return;
    isExamActive = false;

    console.log("Kicking user:", reason);

    fetch('{{ route("exam.reportViolation") }}', {
        method: 'POST',
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            user_exam_id: userExamId,
            status: "is_blocked",
            reason: reason
        })
    })
    .finally(() => {
        window.location.href = `/exam/blocked/${examId}`;
    });
}

// pindah tab
document.addEventListener("visibilitychange", () => {
    if (document.hidden && isExamActive && !isNavigating) {
        kickUser("Pindah tab / minimize");
    }
});

// fullscreen
document.addEventListener("fullscreenchange", () => {
    if (document.fullscreenElement) {
        wasFullscreen = true;
    } else {
        if (wasFullscreen && isExamActive && !isNavigating) {
            kickUser("Keluar fullscreen");
        }
    }
});

// 🔥 MULTI TAB FIX (tidak false trigger lagi)
// setTimeout(() => {
//     if (sessionStorage.getItem("exam_running")) {
//         kickUser("Multi tab terdeteksi");
//     } else {
//         sessionStorage.setItem("exam_running", "true");
//     }
// }, 100);

// hapus hanya kalau pindah soal
window.addEventListener("beforeunload", () => {
    if (isNavigating) {
        sessionStorage.removeItem("exam_running");
    }
});

/**
 * =========================
 * 3. DOM READY (FORM & NAV)
 * =========================
 */
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('answerForm');

    async function saveAnswer() {
        const formData = new FormData(form);

        const response = await fetch("{{ route('exam.save') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: formData
        });

        const result = await response.json();

        console.log(response.status, result);

        return response.ok;
    }

    // 🔥 NAVIGASI (fix utama)
    window.navigateSoal = async function(id) {
        isNavigating = true;
        await saveAnswer();

        window.location.href =
            "{{ route('exam.show', ['exams' => $exams->id, 'bankSoal' => 'ID']) }}"
            .replace('ID', id);
    };

    // radio click
    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', async function() {

            document.querySelectorAll('.answer-option').forEach(label => {
                label.classList.remove('border-blue-500','bg-blue-100','ring-2','ring-blue-300');
                label.classList.add('hover:bg-blue-50');
            });

            this.closest('.answer-option').classList.add(
                'border-blue-500','bg-blue-100','ring-2','ring-blue-300'
            );

            await saveAnswer();
        });
    });

    // tombol ragu
    const markBtn = document.getElementById('markBtn');
    if (markBtn) {
        markBtn.addEventListener('click', async () => {
            let raguInput = document.getElementById('raguInput');
            raguInput.value = raguInput.value == 1 ? 0 : 1;

            await saveAnswer();
        });
    }

    // tombol navigasi
    document.querySelectorAll('.nextBtn, .prevBtn, .number-btn')
        .forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                if (id) navigateSoal(id);
            });
        });
});

/**
 * =========================
 * 4. TIMER
 * =========================
 */
function startTimer(targetEndTime) {
    const timerEl = document.getElementById('timer');
    if (!timerEl) return;

    const interval = setInterval(() => {
        const now = new Date().getTime();
        const distance = targetEndTime - now;

        if (distance <= 0) {

            isFinishing = true; // 🔥 penting
            isExamActive = false;

            clearInterval(interval);
            timerEl.innerHTML = "00:00";

            // 🔥 AUTO SUBMIT (AMAN)
            fetch("{{ route('exam.finish', $userExam->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                }
            }).then(() => {
                window.location.href = "{{ route('exam.hasil', $userExam->id) }}";
            });

            return;
        }

        const minutes = Math.floor(distance / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerEl.innerHTML =
            `${minutes.toString().padStart(2,'0')}:` +
            `${seconds.toString().padStart(2,'0')}`;

        if (distance < 5 * 60 * 1000) {
            timerEl.classList.add('text-red-700','animate-pulse');
        }

    }, 1000);
}

// jalanin timer
startTimer(endTime);
</script>