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
            <h4>Profile siswa</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col gap-4">
    <div class="flex-shrink-0 flex bg-white w-full rounded-md overflow-hidden shadow-md gap-8 p-4">
        <div class="flex-shrink-0 flex justify-center px-2">
            <div class="overflow-hidden aspect-square rounded-full w-24 h-24">
                <img src="{{$student['user']['profile']}}" alt="Profile picture" width="100%" height="100%">
            </div>
        </div>
        <div class="flex-1 flex flex-col gap-2">
            <div class="flex-shrink-0">
                <h1 class="text-lg font-semibold">{{$student['user']['name']}}</h1>
            </div>
            <div class="flex-shrink-0">
                <div class="flex gap-2">
                    <div class="flex-shrink-0">
                        <p>Nomor Induk Siswa</p>
                        <p>Email</p>
                        <p>Kelas saat ini</p>
                        <p>Bergabung pada</p>
                    </div>
                    <div class="flex-shrink-0">
                        <p>:</p>
                        <p>:</p>
                        <p>:</p>
                        <p>:</p>
                    </div>
                    <div class="flex-1 font-semibold">
                        <p>{{$student['nis']}}</p>
                        <p>{{$student['user']['email']}}</p>
                        <p>{{$lastClass == null ? '-' : $lastClass['name']}}</p>
                        <p>{{$student['user']['created_at']}}</p>
                    </div>
                </div>
            </div>
            <div class="flex-shrink-0 mt-2">
                <a href="/admin/students/{{$student['id']}}/edit" class="btn btn-primary px-4">Edit</a>
            </div>
        </div>
    </div>
    <div class="flex-1">
        <div class="flex flex-col gap-4">
            <div class="flex-shrink-0">
                <h4 class="pl-4 text-lg text-gray-700 font-semibold">Riwayat kelas</h4>
            </div>
            <div class="flex-1">
                <table style="width: 50rem;" class="min-w-full md:w-full">
                    <thead class="bg-blue-500 text-white">
                        <tr class="text-left *:py-2">
                            <th rowspan="1" class="w-12 text-center">#</th>
                            <th rowspan="1">Nama</th>
                            <th rowspan="1" class="w-52">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $student['classrooms'] as $classroom )
                            <tr class="text-left *:py-2">
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>
                                    <div class="flex flex-col">
                                        <h4 class="flex-shrink-0">{{$classroom['name']}}</h4>
                                        <p class="text-gray-600">{{ $classroom['academic_year']['name'] }}</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="/admin/students/{{$classroom['pivot']['id']}}" class="btn btn-primary">Lihat</a>
                                        <form action="/admin/student-management/classrooms/{{$student['id']}}/{{$classroom['pivot']['classroom_id']}}" method="post" delete-attribute="true" title-attribute="Hapus {{$student['user']['name']}} dari kelas?" text-attribute="Item tidak dapat dikembalikan!">
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
    </div>
</div>
@endsection