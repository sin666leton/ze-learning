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
            <h4>Halaman tahun ajaran</h4>
        </div>
    </div>
</div>
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md h-full">
    <div class="flex-shrink-0 flex items-center justify-between gap-2 px-4 py-4">
        <div class="flex-1">
            <div class="flex flex-row gap-2 items-center">
                <a href="/admin/academic-years/create" class="btn btn-primary">Tambah</a>
                <span class="bg-blue-100 border border-blue-500 text-blue-500 py-1 px-2 rounded-md">Total {{$academicYear->total()}}</span>
            </div>
        </div>
        <div class="flex-shrink-0">
            <form action="/search">
                <input type="text" name="search" class="focus:outline-none focus:border-gray-500 border border-gray-300 shadow-sm px-2 py-1 rounded-md" placeholder="Cari...">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 py-1 px-2 rounded-md text-white duration-100">Cari</button>
            </form>
        </div>
    </div>
    <div class="flex-1 overflow-scroll">
        <table style="width: 50rem;" class="min-w-full md:w-full">
            <thead class="bg-blue-500 text-white">
                <tr class="text-left *:py-2">
                    <th rowspan="1" class="w-12 text-center">#</th>
                    <th rowspan="1">Tahun ajaran</th>
                    <th rowspan="1" class="w-40">Semester</th>
                    <th rowspan="1" class="w-40">Kelas</th>
                    <th rowspan="1" class="w-52">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $academicYear->items() as $item )
                    <tr class="text-left *:py-2">
                        <td class="text-center">{{$loop->iteration + ($academicYear->currentPage() - 1) * $academicYear->perPage()}}</td>
                        <td>
                            <div class="flex flex-col">
                                <h4 class="flex-shrink-0">{{$item->name}}</h4>
                            </div>
                        </td>
                        <td>{{$item['semesters_count']}} Semester</td>
                        <td>{{$item['classrooms_count']}} Kelas</td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <!-- <a href="/admin/classroom/{{$item->id}}" class="btn btn-primary">Lihat</a> -->
                                <a href="/admin/academic-years/{{$item['id']}}/edit" class="btn btn-warning">Edit</a>
                                <form action="/admin/academic-years/{{$item['id']}}" method="post" delete-attribute="true" title-attribute="Hapus tahun ajaran {{$item->name}}?" text-attribute="Item tidak dapat dikembalikan!">
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
    <div class="flex-shrink-0 pb-2">
        <div class="flex items-center justify-center">
            <div class="flex items-center gap-4">
                @if ($academicYear->currentPage() > 1)
                    <a href="{{$academicYear->previousPageUrl()}}"><</a>
                @else
                    <span><</span>
                @endif

                <div class="flex gap-2.5">
                    @for ($i = 1; $i <= $academicYear->lastPage(); $i++)
                        @if ($i == $academicYear->currentPage())
                            <span class="current-page">{{ $i }}</span>
                        @else
                            <a href="{{ $academicYear->url($i) }}" class="page-link">{{ $i }}</a>
                        @endif
                    @endfor
                </div>
    
                @if ($academicYear->currentPage() < $academicYear->lastPage())
                    <a href="{{ $academicYear->nextPageUrl() }}" class="next">></a>
                @else
                    <span class="disabled">></span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@endsection