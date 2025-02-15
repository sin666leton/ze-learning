@extends('components.layout.student')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">{{$quiz['title']}}</h4>
        </div>
        <div class="flex-1">
            <h4>Halaman penugasan</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col gap-2">
    <div class="flex-shrink-0 flex flex-col bg-white p-4 shadow-md rounded-md gap-5">
        <div class="flex-shrink-0 flex items-center justify-between">
            <div class="flex-1">
                <h4 class="text-gray-500">Kuis</h4>
            </div>
            <div class="flex-shrink-0">
                @if ($quiz['stat'] == 'Dibuka')
                    <h4 class="text-gray-500">
                        Berakhir pada {{$quiz['ended_at']}}
                    </h4>
                @elseif ($quiz['stat'] == 'Selesai')
                    <h4 class="text-red-500">
                        Telah berakhir
                    </h4>
                @endif
            </div>
        </div>
        <div class="flex-shrink-0 flex flex-col">
            <div class="flex-shrink-0">
                <h1 class="text-lg font-semibold">{{$quiz['title']}}</h1>
            </div>
            <div class="flex-shrink-0">
                <p>{{$quiz['content']}}</p>
            </div>
            <div class="flex-shrink-0 mt-4">
                <div class="flex gap-2 text-sm">
                    <div class="flex-shrink-0">
                        <p>Nilai</p>
                        <p>Jumlah soal</p>
                        <p>Durasi pengerjaan</p>
                    </div>
                    <div class="flex-shrink-0">
                        <p>:</p>
                        <p>:</p>
                        <p>:</p>
                    </div>
                    <div class="flex-1 font-semibold">
                        @if ($quiz['score'])
                            <p class="font-semibold">{{$quiz['score']['point']}}</p>
                        @else
                            <p>-</p>
                        @endif
                        <p>2</p>
                        <p>10 menit</p>
                    </div>
                </div>
            </div>
            @if (!$quiz['score'])
                <div class="flex-shrink-0 mt-4">
                    <button type="button" id="attempt" class="btn btn-primary flex items-center gap-2">Kerjakan</button>
                </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    const quizID = {{$quiz['id']}};
    const subjectID = {{$quiz['subject_id']}};

    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        const attemptButton = document.getElementById('attempt');

        attemptButton.addEventListener('click', async function (event) {
            try {
                this.setAttribute('disabled', 'true');
                this.innerHTML = `
                    <svg class="animate-spin w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/>
                    </svg> Loading`;
                const request = await fetch('/students/attempt/quiz', {
                    method: 'POST',
                    headers: {
                        'Content-type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        quiz_id: quizID,
                        subject_id: subjectID
                    }),
                    credentials: "include"
                });

                const response = await request.json();

                if (request.status !== 200) {
                    throw new Error(response.message);
                }

                return window.location.href = '/students/quizzes/questions';
            } catch (error) {
                console.log(error);
                if (error.message !== undefined) {
                    Toast.fire({
                        title: error.message,
                        icon: 'error'
                    });
                }
            } finally {
                this.removeAttribute('disabled');
                this.innerHTML = "Kerjakan"
            }
        });
    });
</script>
@endpush
@endsection
