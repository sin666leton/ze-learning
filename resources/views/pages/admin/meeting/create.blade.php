@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col p-2">
        <div class="flex-1">
            <h2 class="text-xl">Tambah pertemuan</h4>
        </div>
        <div class="flex-1">
            <h4>{{$subject->semester->name}} - {{$subject->classroom->academicYear->name}}</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4">
    <form action="/admin/meetings" method="post" class="form-group">
        @csrf
        <input type="hidden" name="subject_id" value="{{$subject}}">
        <div class="flex gap-4">
            <div class="flex-1 form-control">
                <label for="classroom">Kelas</label>
                <select name="classroom_id" id="classroom">
                    <option value="">{{$subject->classroom->name}}</option>
                </select>
            </div>
            <div class="flex-1 form-control">
                <label for="classroom">Mata pelajaran</label>
                <select name="subject_id" id="subject">
                    <option value="{{$subject->id}}">{{$subject->name}}</option>
                </select>
            </div>
        </div>
        <div class="form-control">
            <label for="name">Nama</label>
            <input type="text" name="name" id="name">
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection