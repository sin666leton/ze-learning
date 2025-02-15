<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Learning</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        * {
            font-family: 'Poppins';
        }
    </style>
</head>
<body class="overflow-x-hidden">
    <div class="flex flex-col justify-between h-screen gap-4">
        <!-- Header -->
        <div class="flex-shrink-0 bg-blue-500 shadow-md p-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex items-center gap-2">
                    <div class="flex-shrink-0">
                        <h4>Waktu tersisa</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <span id="timeLeft">00:00:00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1">
            <form method="POST" action="/user/answer/quiz" class="flex flex-col items-center justify-center gap-4" attribute-danger="true">
                @csrf
                @foreach ( $questions as $question )
                    <input type="hidden" name="">
                    <div class="flex-shrink-0 flex flex-col gap-4 bg-white shadow-md rounded-md w-1/2 p-4">
                        <div class="flex-shrink-0 flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-gray-500">Soal no. {{$loop->iteration}}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <h4 class="text-blue-500">Poin {{$question['point']}}</h4>
                            </div>
                        </div>
                        <div class="flex-1 mb-4">
                            {!! $question['content'] !!}
                        </div>
                        @if ($question['type'] == 'mcq')
                            <div class="flex-shrink-0">
                                <div class="flex flex-col gap-2.5">
                                    @foreach ( $question['options'] as $key => $choice )
                                        <div class="flex-shrink-0">
                                            @if ($question['answer'] && $question['answer'][0]['content'] == $choice['content'])
                                                <input type="radio" name="answer[{{$question['id']}}]" value="{{$choice['content']}}" id="answer-{{$question['id']}}-{{$key}}" class="hidden peer" attribute-onchange="true" data-attribute="{{$question['id']}}" checked="true">
                                            @else
                                                <input type="radio" name="answer[{{$question['id']}}]" value="{{$choice['content']}}" id="answer-{{$question['id']}}-{{$key}}" class="hidden peer" attribute-onchange="true" data-attribute="{{$question['id']}}">
                                            @endif
                                            <label for="answer-{{$question['id']}}-{{$key}}" class="inline-block border border-gray-500 rounded-sm w-full p-2 peer-checked:border-blue-600 peer-checked:text-white peer-checked:bg-blue-500">{{$choice['content']}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else 
                            <div class="flex-shrink-0">
                                @if ($question['answer'])
                                    <textarea name="answer[{{$question['id']}}]" id="" class="w-full rounded-md border border-gray-500 focus:outline-none focus:border-black p-2" attribute-onchange="true" data-attribute="{{$question['id']}}">{{$question['answer'][0]['content']}}</textarea>
                                @else
                                    <textarea name="answer[{{$question['id']}}]" id="" class="w-full rounded-md border border-gray-500 focus:outline-none focus:border-black p-2" attribute-onchange="true" data-attribute="{{$question['id']}}"></textarea>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
                <div class="flex-shrink-0 mt-4 w-1/2">
                    <button type="submit" class="btn btn-primary w-full py-3 rounded-full">Selesai</button>
                </div>
            </form>
        </div>

        <!-- footer -->
        <div class="flex-shrink-0 flex items-center justify-center bg-white border-t border-gray-200 py-4">
            <h4 class="text-gray-500 text-sm">Copyright &copy; Zidan 2024</h4>
        </div>
    </div>
    @vite('resources/js/app.js')
    <script>
        const attempt_id = {{ $attempt_id }};
        let timeLeft = {{ $time_left }};
        const timeLeftField = document.getElementById('timeLeft');

        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');

            document.querySelectorAll('[attribute-onchange=true]').forEach(item => {
                item.addEventListener('change', async function () {
                    const request = await fetch('/students/answer/questions', {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        method: "POST",
                        body: JSON.stringify({
                            question_id: this.getAttribute('data-attribute'),
                            content: this.value
                        }),
                        credentials: 'same-origin'
                    });

                    if (request.status !== 200) {
                        const response = await request.json();
                        
                        Toast.fire({
                            title: response.message,
                            icon: 'error'
                        });
                    }
                });
            });

            async function submitForm() {
                const request = await fetch('/students/attempt/quiz/'+attempt_id, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    method: 'DELETE',
                    credentials: 'same-origin'
                });

                switch (request.status) {
                    case 200:
                        return window.location.replace('/students/dashboard');
                
                    default:
                        const response = await request.json();

                        Toast.fire({
                            title: response.message,
                            icon: 'error'
                        });

                        break;
                }
            }

            function updateTimer() {
                let timerHours = Math.floor(timeLeft / 3600);
                let timerMinutes = Math.floor((timeLeft % 3600) / 60);
                let timerSeconds = Math.floor(timeLeft % 60);

                let timeString = `${timerHours.toString().padStart(2, '0')}:${timerMinutes.toString().padStart(2, '0')}:${timerSeconds.toString().padStart(2, '0')}`;

                timeLeftField.textContent = timeString;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    submitForm();
                } else {
                    timeLeft--;
                }
            }

            let timer = setInterval(updateTimer, 1000);
        
            document.querySelector('form[attribute-danger=true]').addEventListener('submit', function (event) {
                event.preventDefault();
    
                Swal.fire({
                    title: "Akhiri kuis?",
                    text: "Kuis tidak dapat diulang kembali!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Submit",
                    cancelButtonText: "Batal"
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });
            });
        });
    </script>
</body>
</html>