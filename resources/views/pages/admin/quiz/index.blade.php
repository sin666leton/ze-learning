@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Kelas</h4>
        </div>
        <div class="flex-1">
            <h4>Halaman kelas</h4>
        </div>
    </div>
</div>
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md h-full">
    <div class="flex-shrink-0 flex items-center justify-between gap-2 px-4 py-4">
        <div class="flex-1">
            <div class="flex flex-row gap-2 items-center">
                <a href="/admin/quizzes/create?subject={{$subject->id}}" class="btn btn-primary">Tambah</a>
                <span class="bg-blue-100 border border-blue-500 text-blue-500 py-1 px-2 rounded-md">Total {{$total}}</span>
            </div>
        </div>
        <div class="flex-shrink-0">
        </div>
    </div>
    <div class="flex-1 overflow-scroll">
        <table style="width: 50rem;" class="min-w-full md:w-full">
            <thead class="bg-blue-500 text-white">
                <tr class="text-left *:py-2">
                    <th rowspan="1" class="w-12 text-center">#</th>
                    <th rowspan="1">Judul</th>
                    <th rowspan="1" class="w-40">Status</th>
                    <th rowspan="1" class="w-40">Dibuat pada</th>
                    <th rowspan="1" class="w-52">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $subject['quizzes'] as $quiz )
                    <tr class="text-left *:py-2">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td>
                            <h4>{{$quiz->title}}</h4>
                        </td>
                        <td>
                            {{$quiz->status}}
                        </td>
                        <td>
                            {{$quiz->created_at->format('d-m-Y h:i')}}
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="/admin/quizzes/{{$quiz->id}}" class="btn btn-primary">Lihat</a>
                                <a href="/admin/quizzes/{{$quiz->id}}/edit" class="btn btn-warning">Edit</a>
                                <form action="/admin/quizzes/{{$quiz->id}}" method="post" delete-attribute="true" title-attribute="Hapus kuis {{$quiz->title}}?" text-attribute="Item tidak dapat dikembalikan!">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@endsection