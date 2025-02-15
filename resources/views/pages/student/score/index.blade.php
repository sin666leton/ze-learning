@extends('components.layout.student')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Nilai</h4>
        </div>
        <div class="flex-1">
            <h4>Halaman penilaian</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md h-full">
    <div class="flex-shrink-0 flex items-center justify-between gap-2 px-4 py-4">
        <div class="flex-1">
        </div>
        <div class="flex-shrink-0">
            <form method="GET">
                <select name="classroom" id="" class="py-1 px-2 rounded-md bg-white border border-gray-500">
                    <option value="" disabled selected>Kelas</option>
                    @foreach ( $student as $pivot )
                        @if ($pivot['id'] == request()->classroom)
                            <option value="{{$pivot['id']}}" selected="true">{{$pivot['classroom']['name']}}</option>
                        @else
                            <option value="{{$pivot['id']}}">{{$pivot['classroom']['name']}}</option>
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
                    <th rowspan="1" class="px-4">Mata pelajaran</th>
                    <th rowspan="1" class="w-20">Tipe</th>
                    <th rowspan="1" class="w-60">Judul</th>
                    <th rowspan="1" class="w-24 px-4">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $scores as $score )
                    <tr class="text-left *:py-2">
                        <td class="px-4">
                            {{$score['subject']}}
                        </td>
                        <td class="w-20">
                            {{ $score['type'] }}
                        </td>
                        <td class="w-20">
                            <p class="truncate w-60">
                                {{ $score['title'] }} Lorem ipsum, dolor sit amet consectetur adipisicing elit. Facere officiis ad fugiat. Totam, error tempore quaerat possimus sit aspernatur! Corporis facere a quia reiciendis repudiandae magnam sit saepe architecto odit.
                            </p>
                        </td>
                        <td class="px-4">
                            {{ $score['point'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection