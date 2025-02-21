@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Edit pengguna</h4>
        </div>
        <div class="flex-1">
            <h4>Form edit pengguna</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4">
    <form action="/admin/students/{{$student['id']}}" method="post" class="form-group">
        @csrf
        @method('PUT')
        <div class="form-control">
            <label for="email">Email</label>
            <input type="email" name="email" id="" value="{{$student['user']['email']}}">
        </div>
        <div class="form-control">
            <label for="name">NIS</label>
            <input type="number" name="nis" id="" value="{{$student['nis']}}">
        </div>
        <div class="form-control">
            <label for="name">Nama</label>
            <input type="text" name="name" id="" value="{{$student['user']['name']}}">
        </div>
        <div class="form-control">
            <label for="password">Password</label>
            <input type="text" name="password" id="">
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection