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

            <form action="{{ route('exam.finish', $userExam->id) }}" method="POST">
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

                        if (!$jawaban || !$jawaban->answer_id) {
                            $color = 'bg-gray-200';
                        } elseif ($jawaban->ragu) {
                            $color = 'bg-yellow-400';
                        } else {
                            $color = 'bg-green-500 text-white';
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
    const duration = {{ $exams->time }}; // menit
const startedAt = new Date("{{ optional($userExam->started_at)->toIso8601String() ?? now()->toIso8601String() }}").getTime();    const endTime = startedAt + (duration * 60 * 1000);


document.addEventListener("DOMContentLoaded", function() {

    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const form = document.getElementById('answerForm');

let isExamActive = true;

function kickUser() {
    if (!isExamActive) return;
    isExamActive = false;

    // Ambil data langsung dari variabel Blade
    const userId = "{{ auth()->user()->id }}";
    const examId = "{{ $userExam->exam_id }}"; // ✅ BENAR
    fetch('/exam/violation', {
        method: 'POST',
        headers: {
            "X-CSRF-TOKEN": csrf,
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            exam_id: examId,
            user_id: userId,
            status: "is_blocked",
            reason: "Mencoba pindah tab atau keluar fullscreen"
        })
    })
    .then(response => {
            window.location.href = `/exam/blocked/${examId}`;
        })
        .catch(err => {
            window.location.href = `/exam/blocked/${examId}`;
        });
}

// pindah tab / minimize
document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
        kickUser();
    }
});

// keluar fullscreen
document.addEventListener("fullscreenchange", () => {
    if (!document.fullscreenElement) {
        kickUser();
    }
});

// multi tab
if (localStorage.getItem("exam_open")) {
    kickUser();
} else {
    localStorage.setItem("exam_open", true);
}

window.addEventListener("beforeunload", () => {
    localStorage.removeItem("exam_open");
});

// =====================
// 🔐 ANTI CHEAT END
// =====================

    async function saveAnswer() {
        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('exam.save') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Accept": "application/json"
                },
                credentials: "same-origin",
                body: formData
            });

            const result = await response.json();
            console.log("SAVE SUCCESS:", result);
        } catch (error) {
            console.error("SAVE ERROR:", error);
        }
    }

    function updateSidebarColor() {
        const soalId = document.querySelector('input[name="bank_soal_id"]').value;
        const selected = document.querySelector('input[name="answer_id"]:checked');
        const ragu = document.getElementById('raguInput').value;

        const btn = document.getElementById('btn-' + soalId);

        btn.classList.remove('bg-gray-200','bg-green-500','bg-yellow-400','text-white');

        if (!selected && ragu == 0) {
            btn.classList.add('bg-gray-200');
        } 
        else if (ragu == 1) {
            btn.classList.add('bg-yellow-400');
        } 
        else {
            btn.classList.add('bg-green-500','text-white');
        }
    }

    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', async function() {

            document.querySelectorAll('.answer-option').forEach(label => {
                label.classList.remove(
                    'border-blue-500',
                    'bg-blue-100',
                    'ring-2',
                    'ring-blue-300'
                );
                label.classList.add('hover:bg-blue-50');
            });

            const selectedLabel = this.closest('.answer-option');

            selectedLabel.classList.remove('hover:bg-blue-50');
            selectedLabel.classList.add(
                'border-blue-500',
                'bg-blue-100',
                'ring-2',
                'ring-blue-300'
            );

            await saveAnswer();
            updateSidebarColor();
        });
    });

    document.getElementById('markBtn').addEventListener('click', async () => {
        let raguInput = document.getElementById('raguInput');
        raguInput.value = raguInput.value == 1 ? 0 : 1;

        await saveAnswer();
        updateSidebarColor();
    });

    async function navigate(id) {
        await saveAnswer();
        window.location.href =
            "{{ route('exam.show', ['exams' => $exams->id, 'bankSoal' => 'ID']) }}"
            .replace('ID', id);
    }

    document.querySelectorAll('.nextBtn, .prevBtn, .number-btn')
        .forEach(btn => {
            btn.addEventListener('click', function() {
                navigate(this.dataset.id);
            });
        });

});


// =====================
// ⏱ TIMER START
// =====================
function startTimer(endTime) {
    const timerEl = document.getElementById('timer');

    const interval = setInterval(() => {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance <= 0) {
            clearInterval(interval);
            timerEl.innerHTML = "00:00";

            // auto submit
            const finishForm = document.querySelector('form[action*="exam.finish"]');
            if (finishForm) finishForm.submit();

            return;
        }

        const minutes = Math.floor(distance / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerEl.innerHTML =
            `${minutes.toString().padStart(2,'0')}:` +
            `${seconds.toString().padStart(2,'0')}`;

        // 🔴 warning kalau sisa < 5 menit
        if (distance < 5 * 60 * 1000) {
            timerEl.classList.add('text-red-700', 'animate-pulse');
        }

    }, 1000);
}

// jalankan timer
startTimer(endTime);
// =====================
// ⏱ TIMER END
// =====================

</script>