@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Penilaian</h4>
        </div>
        <div class="flex-1">
            <h4>Form Penilaian</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4">
    <form action="/admin/scores/{{$score['id']}}" method="post" class="form-group">
        @csrf
        @method('PUT')
        <div class="flex gap-4">
            <div class="flex-1 form-control">
                <label for="subject">Mata pelajaran</label>
                <input type="text" class="bg-gray-100" name="" id="" value="{{request()->type == 'assignment' ? $score['scoreable']['assignment']['subject']['name'] : $score['scoreable']['subject']['name']}}" disabled>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="flex-1 form-control">
                <label for="classroom">Siswa</label>
                @if (request()->type == 'assignment')
                    <input type="text" class="bg-gray-100" name="" id="" value="{{$score['student_classroom']['student']['user']['name']}} - {{$score['student_classroom']['student']['nis']}}" disabled>
                @else
                    <input type="text" class="bg-gray-100" name="" id="" value="{{$score['student_classroom']['student']['user']['name']}} - {{$score['student_classroom']['student']['nis']}}" disabled>
                @endif
            </div>
            @if (request()->type == 'assignment')
                <div class="flex-1 form-control">
                    <label for="subject">Penugasan</label>
                    <input type="text" class="bg-gray-100" name="" id="" value="{{$score['scoreable']['assignment']['title']}}" disabled>
                </div>
                <input type="hidden" name="type" value="assignments">
                <input type="hidden" name="belongsTo" value="{{ $score['scoreable']['assignment']['id'] }}">
                @else
                <div class="flex-1 form-control">
                    <label for="subject">Kuis</label>
                    <input type="text" class="bg-gray-100" name="" id="" value="{{$score['scoreable']['title']}}" disabled>
                </div>
                <input type="hidden" name="type" value="quizzes">
                <input type="hidden" name="belongsTo" value="{{ $score['scoreable']['id'] }}">
            @endif
        </div>
        <div class="flex gap-4">
            <div class="flex-1 form-control">
                <label for="score">Nilai</label>
                <input type="number" name="point" id="score" min="0" max="100" value="{{$score['point']}}">
            </div>
        </div>
        <div class="flex gap-2">
            @if ($score['published'])
                <input type="hidden" name="published" id="" value="0">
                <input type="checkbox" name="published" id="" value="1" checked="true">
            @else
                <input type="hidden" name="published" id="" value="0">
                <input type="checkbox" name="published" id="" value="1">
            @endif
            <label for="validated">Publikasi nilai</label>
        </div>
        <div class="mt-4">
            <button class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection