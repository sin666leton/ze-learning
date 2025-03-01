@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1 text-lg">
            @foreach ( $navLink as $link => $value )
                <a href="{{ $value['url'] }}">{{ $value['label'] }}</a> {{ $loop->last ? '' : '>' }}
            @endforeach
        </div>
        <div class="flex-1">
            <h4>Halaman penugasan</h4>
        </div>
    </div>
</div>
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md h-full">
    <div class="flex-shrink-0 flex items-center justify-between gap-2 px-4 py-4">
        <div class="flex-1">
            <div class="flex flex-row gap-2 items-center">
                <a href="/admin/assignments/create?subject={{$subject['id']}}" class="btn btn-primary">Tambah</a>
                <span class="bg-blue-100 border border-blue-500 text-blue-500 py-1 px-2 rounded-md">Total {{$subject['assignments_count']}}</span>
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
                @foreach ( $subject['assignments'] as $assignment )
                    <tr class="text-left *:py-2">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td>
                            <h4 class="flex-shrink-0">{{$assignment['title']}}</h4>
                        </td>
                        <td>
                            {{$assignment['stat']}}
                        </td>
                        <td>
                            {{$assignment['created']}}
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="/admin/assignments/{{$assignment['id']}}" class="btn btn-primary">Lihat</a>
                                <a href="/admin/assignments/{{$assignment['id']}}/edit" class="btn btn-warning">Edit</a>
                                <form action="/admin/assignments/{{$assignment['id']}}" method="post" delete-attribute="true" title-attribute="Hapus tugas Nama tugas?" text-attribute="Item tidak dapat dikembalikan!">
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