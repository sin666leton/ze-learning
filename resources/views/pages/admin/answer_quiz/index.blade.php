@extends('components.layout.admin')
@section('header')

@endsection
@section('content')
<div class="flex flex-col gap-2 justify-between bg-white rounded-md shadow-md mb-4">
    <div class="flex-shrink-0">
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2 text-gray-500">
            <div class="flex-1">
                <span>{{$score->validated ? 'Tervalidasi' : 'Belum divalidasi'}}</span>
            </div>
            <div class="flex-shrink-0">
                <span>{{$score->created}}</span>
            </div>
        </div>
    </div>
    <div class="flex-1 px-4 py-2">
        <div class="flex flex-col gap-3">
            <div class="flex-shrink-0">
                <h4 class="truncate text-lg font-semibold text-blue-500">{{$score->name}}</h4>
            </div>
            <div class="flex-1 text-gray-600">
                <div class="flex flex-col">
                    <div class="flex-shrink-0">
                        <span>Nilai: </span>
                        <span class="font-semibold">{{$score->point}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="flex flex-col gap-4" id="dynamicField">
    @foreach ( $quiz->questions as $question )
        <div class="flex-shrink-0 flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md gap-2">
            <div class="flex-shrink-0 flex items-center justify-between px-4 py-2 border-b border-gray-300">
                <div class="flex-1">
                    @if ($question->type == 'essay')
                        Penilaian tidak otomatis
                    @else
                        {{ $question->answerQuestion[0]->is_correct ? "Benar" : "Salah" }}
                    @endif
                </div>
                <div class="flex-shrink-0">
                    Poin {{ $question->point }}
                </div>
            </div>
            <div class="flex-1 px-4 pt-4">
                <div class="flex flex-col gap-2">
                    <div class="flex-shrink-0">
                        <span class="text-gray-600 font-semibold">Pertanyaan</span>
                    </div>
                    <div class="flex-1">
                        {!! $question->content !!}
                    </div>
                </div>
            </div>
            <div class="flex-shrink-0 px-4 py-4">
                <div class="flex flex-col gap-2">
                    <div class="flex-shrink-0">
                        <span class="text-gray-600 font-semibold">Jawaban</span>
                    </div>
                    <div class="flex-shrink-0">
                        <p>{!! $question->answerQuestion[0]->content !!}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection