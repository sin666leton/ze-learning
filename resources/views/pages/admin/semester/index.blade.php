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
            <h4>Halaman semester</h4>
        </div>
    </div>
</div>
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md h-full">
    <div class="flex-shrink-0 flex items-center justify-between gap-2 px-4 py-4">
        <div class="flex-1">
            <div class="flex flex-row gap-2 items-center">
                <a href="/admin/semesters/create" class="btn btn-primary">Tambah</a>
                <span class="bg-blue-100 border border-blue-500 text-blue-500 py-1 px-2 rounded-md">Total {{$total}}</span>
            </div>
        </div>
        <div class="flex-shrink-0">
            <form action="/admin/semesters">
                <select name="academic_year" class="bg-white border border-gray-300 shadow-sm px-2 py-1 rounded-md" id="">
                    <option value="" selected disabled>Tahun ajaran</option>
                    @foreach ( $academicYear as $item )
                        @if ($item['id'] == request()->academic_year)
                            <option value="{{$item['id']}}" selected="true">{{$item['name']}}</option>
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
                    <th rowspan="1">Nama</th>
                    <th rowspan="1" class="w-52">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $semesters as $item )
                    <tr class="text-left *:py-2">
                        <td class="text-center">
                            <h4>{{$loop->iteration}}</h4>
                        </td>
                        <td>
                            <h4>{{$item['name']}}</h4>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <!-- <a href="/admin/classroom/{{$item['name']}}" class="btn btn-primary">Lihat</a> -->
                                <a href="/admin/semesters/{{$item['id']}}/edit" class="btn btn-warning">Edit</a>
                                <form action="/admin/semesters/{{$item['id']}}" method="post" delete-attribute="true" title-attribute="Hapus {{$item['name']}}?" text-attribute="Item tidak dapat dikembalikan!">
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