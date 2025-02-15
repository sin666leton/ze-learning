@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Tambah mata pelajaran</h4>
        </div>
        <div class="flex-1">
            <h4>Form tambah mata pelajaran</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4">
    <form action="/admin/subjects" method="post" class="form-group">
        @csrf
        <div class="flex gap-4">
            <div class="flex-1 form-control">
                <label for="classroom">Kelas</label>
                <select name="classroom_id" id="classroom" class="bg-gray-100">
                    <option value="{{$classroom->id}}">{{$classroom->name}}</option>
                </select>
            </div>
            <div class="flex-1 form-control">
                <label for="semester">Semester</label>
                <select name="semester_id" id="semester">
                    @foreach ( $classroom->academicYear->semesters()->get() as $item )
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-control">
            <label for="name">Nama mata pelajaran</label>
            <input type="text" name="name" id="name">
        </div>
        <div class="form-control">
            <label for="kkm">Kriteria Ketuntasan Minimal</label>
            <input type="number" name="kkm" id="kkm" min="1" max="99" placeholder="Default: 70">
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection