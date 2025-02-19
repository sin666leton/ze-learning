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
            <h4>Mata pelajaran pada kelas {{ $classroom['name'] }}</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col gap-4">
    <div class="flex gap-4">
        <div class="flex-1 flex gap-3 border border-gray-200 shadow-md rounded-md overflow-hidden">
            <div class="flex-shrink-0">
                <div class="h-full w-1.5 bg-blue-500"></div>
            </div>
            <div class="flex-shrink-0 pt-4">
                <div class="flex items-center justify-start">
                    <div class="flex-shrink-0 w-10 p-2 aspect-square flex items-center justify-center text-blue-500 bg-blue-200 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path fill="currentColor" d="M96 32C60.7 32 32 60.7 32 96l0 288 64 0L96 96l384 0 0 288 64 0 0-288c0-35.3-28.7-64-64-64L96 32zM224 384l0 32L32 416c-17.7 0-32 14.3-32 32s14.3 32 32 32l512 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-128 0 0-32c0-17.7-14.3-32-32-32l-128 0c-17.7 0-32 14.3-32 32z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex-1 py-4">
                <div class="flex flex-col justify-between">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl">Mata pelajaran</h1>
                    </div>
                    <div class="flex-shrink-0">
                        <h4>{{$classroom['subjects_count']}} item</h4>
                    </div>
                    <div class="flex-shrink-0 mt-4">
                        <a href="?category=subject" class="px-3 py-1 rounded-sm text-sm btn-primary">Lihat</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 flex gap-3 border border-gray-200 shadow-md rounded-md overflow-hidden">
            <div class="flex-shrink-0">
                <div class="h-full w-1.5 bg-orange-500"></div>
            </div>
            <div class="flex-shrink-0 pt-4">
                <div class="flex items-center justify-start">
                    <div class="flex-shrink-0 w-10 p-2 aspect-square flex items-center justify-center text-orange-500 bg-orange-200 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                            <path fill="currentColor" d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex-1 py-4">
                <div class="flex flex-col justify-between">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl">Siswa</h1>
                    </div>
                    <div class="flex-shrink-0">
                        <h4>{{$classroom['students_count']}} orang</h4>
                    </div>
                    <div class="flex-shrink-0 mt-4">
                        <a href="?category=student" class="px-3 py-1 rounded-sm text-sm btn-warning">Lihat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md h-full">
        <div class="flex-shrink-0 flex items-center justify-between gap-2 px-4 py-4">
            <div class="flex-1">
                <div class="flex flex-row gap-2 items-center">
                    <a href="/admin/subjects/create?classroom={{$classroom['id']}}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md duration-100 cursor-pointer">Tambah</a href="/admin/subject/create">
                    <span class="bg-blue-100 border border-blue-500 text-blue-500 py-1 px-2 rounded-md">Total {{$classroom['subjects_count']}}</span>
                </div>
            </div>
            <div class="flex-shrink-0">
                <form action="/admin/classrooms/{{$classroom['id']}}">
                    <select class="bg-white border border-gray-300 shadow-sm px-2 py-1 rounded-md" name="semester" id="">
                        <option value="" default disabled>Semester</option>
                        @foreach ( $classroom['academic_year']['semesters'] as $item )
                            @if ($item['id'] == $defaultSemesterID)
                                <option value="{{$item['id']}}" selected>{{$item['name']}}</option>
                            @else
                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 py-1 px-2 rounded-md text-white duration-100">Cari</button>
                </form>
            </div>
        </div>
        <div class="flex-1 overflow-scroll">
            <table style="width: 50rem;" class="min-w-full md:w-full">
                <thead class="bg-blue-500 text-white">
                    <tr class="text-left *:py-2">
                        <th rowspan="1" class="w-12 text-center">#</th>
                        <th rowspan="1">Mata pelajaran</th>
                        <th rowspan="1" class="w-24">KKM</th>
                        <th rowspan="1" class="w-52">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $classroom['subjects'] as $subject )
                        <tr class="text-left *:py-2">
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>
                                <div class="flex flex-col">
                                    <h4 class="flex-shrink-0">{{$subject['name']}}</h4>
                                </div>
                            </td>
                            <td>{{$subject['kkm']}}</td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <a href="/admin/subjects/{{$subject['id']}}" class="btn btn-primary">Lihat</a>
                                    <a href="/admin/subjects/{{$subject['id']}}/edit" class="btn btn-warning">Edit</a>
                                    <form action="/admin/subjects/{{$subject['id']}}" method="post" delete-attribute="true" title-attribute="Hapus mata pelajaran {{$subject['name']}}?" text-attribute="Item tidak dapat dikembalikan!">
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
@endsection
