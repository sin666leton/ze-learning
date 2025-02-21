@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Detail kuis</h4>
        </div>
        <div class="flex-1">
            <h4>Partisipan</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col gap-6">
    <div class="flex-shrink-0 flex gap-4">
        <div class="flex-1 flex flex-col justify-between gap-4">
            <div class="flex-1 flex gap-3 border border-gray-200 shadow-md rounded-md overflow-hidden">
                <div class="flex-shrink-0">
                    <div class="h-full w-1.5 bg-green-500"></div>
                </div>
                <div class="flex-shrink-0 pt-4">
                    <div class="flex items-center justify-start">
                        <div class="flex-shrink-0 w-10 p-2 aspect-square flex items-center justify-center text-green-500 bg-green-200 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                <path fill="currentColor" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex-1 py-4">
                    <div class="flex flex-col justify-between">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl">Tuntas</h1>
                        </div>
                        <div class="flex-shrink-0">
                            <h4>{{$quiz['attempted']}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 flex gap-3 border border-gray-200 shadow-md rounded-md overflow-hidden">
                <div class="flex-shrink-0">
                    <div class="h-full w-1.5 bg-red-500"></div>
                </div>
                <div class="flex-shrink-0 px-1 pt-4">
                    <div class="flex items-center justify-start">
                        <div class="flex-shrink-0 w-10 p-2 aspect-square flex items-center justify-center text-red-500 bg-red-200 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                <path fill="currentColor" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex-1 py-4">
                    <div class="flex flex-col justify-between">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl">Belum tuntas</h1>
                        </div>
                        <div class="flex-shrink-0">
                            <h4>{{$quiz['not_attempted']}} siswa</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 shadow-md p-8">
            <canvas id="scoreChart"></canvas>
        </div>
    </div>
    <div class="flex-shrink-0 flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4 gap-4">
        <div class="flex-shrink-0 flex items-center justify-between gap-4">
            <div class="flex-1">
                <h4>Kuis</h4>
            </div>
            <div class="flex-shrink-0">
                <h4>{{$quiz->created_at->format('d-m-Y h:i')}}</h4>
            </div>
        </div>
        <div class="flex-shrink-0 flex flex-col">
            <div class="flex-shrink-0">
                <h4 class="text-lg font-semibold">{{$quiz->title}}</h4>
            </div>
            <div class="flex-shrink-0">
                <p>{{$quiz->content}}</p>
            </div>
        </div>
        <div class="flex-shrink-0">
            <div class="flex gap-2 text-sm">
                <div class="flex-shrink-0">
                    <p>Skor</p>
                    <p>Jumlah soal</p>
                    <p>Durasi pengerjaan</p>
                </div>
                <div class="flex-shrink-0">
                    <p>:</p>
                    <p>:</p>
                    <p>:</p>
                </div>
                <div class="flex-1 font-semibold">
                    <p>{{$quiz['total_point']}}</p>
                    <p>{{$quiz['total_question']}}</p>
                    <p>{{$quiz->duration}} menit</p>
                </div>
            </div>
        </div>
        <div class="flex-shrink-0 mt-2">
            <a href="/admin/questions?quiz={{$quiz->id}}" class="btn btn-primary px-4">Soal</a>
            <a target="__blank" href="/admin/export/score/quiz/{{$quiz->id}}" class="btn btn-primary px-4 ">Cetak</a>
        </div>
    </div>
    <div class="flex-1">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div class="col-span-2">
                <div class="px-2">
                    <span class="text-lg font-semibold text-gray-600">
                        Siswa yang berpartisipasi
                    </span>
                </div>
            </div>
            @foreach ( $quiz['scores'] as $item )
                @if ($item->point >= 81)
                    <?php $smart+=1 ?>
                @elseif ($item->point >= 50)
                    <?php $veryGood+=1 ?>
                @elseif ($item->point >= 20)
                    <?php $good+=1 ?>
                @else
                    <?php $bad+=1 ?>
                @endif
                <div class="flex flex-col gap-2 justify-between bg-white rounded-md shadow-md">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2 text-gray-500">
                            <div class="flex-1">
                            </div>
                            <div class="flex-shrink-0">
                                <span>{{$item->created_at}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 px-4 py-2">
                        <div class="flex flex-col gap-3">
                            <div class="flex-shrink-0">
                                <h4 class="truncate text-lg font-semibold text-blue-500">{{$item->studentClassroom->student->user->name}}</h4>
                            </div>
                            <div class="flex-1 text-gray-600">
                                <div class="flex flex-col">
                                    <div class="flex-shrink-0">
                                        <span>Nilai: </span>
                                        <span class="font-semibold">{{$item->point}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="flex items-center gap-2 p-3">
                            <div class="flex-shrink-0">
                                <a href="/admin/answer_quiz/{{$item->id}}?student={{$item->studentClassroom->student_id}}" class="btn btn-primary">Lihat</a>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="/admin/scores/{{$item->id}}/edit?type=quiz" class="btn {{$item->published ? 'btn-success' : 'btn-danger'}}">Validasi</a>
                            </div>
                            <div class="flex-shrink-0">
                                <form action="/admin/scores/{{$item->id}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="assignment">
                                    <input type="hidden" name="belongsToID" value="{{request()->id}}">
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@push('scripts')
    @vite('resources/js/chart.js')
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            const canvasScoreChart = document.getElementById('scoreChart');

            barChart(canvasScoreChart, '{{$bad}}', '{{$good}}', '{{$veryGood}}', '{{$smart}}');
        });
    </script>
@endpush
@endsection