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
    <form action="/admin/scores/{{request()->id}}" method="post" class="form-group">
        @csrf
        @method('PUT')
        <div class="flex flex-col items-center justify-between md:flex-row gap-3">
            <div class="flex-1 form-control">
                <label for="classroom">Kelas</label>
                <input type="text" name="" id="" value="{{$classroom->name}}" class="bg-gray-100" disabled>
            </div>
            <div class="flex-1 form-control">
                <label for="subject">Mata Pelajaran</label>
                <input type="text" name="" id="subject" value="{{$subject->name}}" class="bg-gray-100" disabled>
            </div>
        </div>
        <div class="flex flex-col items-center justify-between md:flex-row gap-3">
            <div class="flex-1 form-control">
                <label for="meeting">Pertemuan</label>
                <input type="text" name="" id="meeting" value="{{$meeting->name}}" class="bg-gray-100" disabled>
            </div>
            <div class="flex-1 form-control">
                <label for="type">Tipe</label>
                <select name="type" id="type" class="bg-gray-100" disabled>
                    <option value="{{$type}}" selected>{{ucfirst($type)}}</option>
                </select>
            </div>
        </div>
        <div class="flex flex-col items-center justify-between md:flex-row gap-3">
            <div class="flex-1 form-control">
                <label for="user">Pengguna</label>
                <select name="user_id" id="user" class="bg-black" disabled>
                    <option value="{{$user->id}}" class="bg-black">{{$user->name}}</option>
                </select>
            </div>
        </div>
        <div class="flex flex-col items-center justify-between md:flex-row gap-3">
            <div class="flex-1 form-control">
                <label for="score">Nilai</label>
                <input type="number" name="score" id="score" value="{{$score->point}}" min="0" max="100">
            </div>
            <div class="flex-1 form-control">
                <label for="validated">Validasi</label>
                <select name="status" id="validated">
                    <option value="1" {{$score->status == "1" ? "selected" : ""}}>Ya</option>
                    <option value="0" {{$score->status == "0" ? "selected" : ""}}>Tidak</option>
                </select>
            </div>
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection