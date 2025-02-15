@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col p-2">
        <div class="flex-1">
            <h2 class="text-xl">Edit {{$meeting->name}}</h4>
        </div>
        <div class="flex-1">
            <h4>{{$meeting->subject->semester->name}} - {{$meeting->subject->classroom->academicYear->name}}</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4">
    <form action="/admin/meetings/{{$meeting->id}}" method="post" class="form-group">
        @csrf
        @method('PUT')
        <input type="hidden" name="subject_id" value="{{$meeting->subject}}">
        <div class="flex gap-4">
            <div class="flex-1 form-control">
                <label for="classroom">Kelas</label>
                <select name="classroom_id" id="classroom">
                    <option value="">{{$meeting->subject->classroom->name}}</option>
                </select>
            </div>
            <div class="flex-1 form-control">
                <label for="classroom">Mata pelajaran</label>
                <select name="subject_id" id="subject">
                    <option value="{{$meeting->subject->id}}">{{$meeting->subject->name}}</option>
                </select>
            </div>
        </div>
        <div class="form-control">
            <label for="name">Nama</label>
            <input type="text" name="name" id="name" value="{{$meeting->name}}">
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection