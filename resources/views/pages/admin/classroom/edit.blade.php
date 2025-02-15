@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Kelas {{$classroom['name']}}</h4>
        </div>
        <div class="flex-1">
            <h4>Form edit kelas</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4">
    <form action="/admin/classrooms/{{$classroom['id']}}" method="post" class="form-group">
        @csrf
        @method('PUT')
        <div class="form-control">
            <label for="name">Nama kelas</label>
            <input type="text" name="name" id="name" value="{{$classroom['name']}}">
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection