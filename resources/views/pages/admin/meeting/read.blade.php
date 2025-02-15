@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
    <div class="flex-1">
        <h2 class="text-xl">{{$meeting->name}}</h4>
    </div>
    <div class="flex-1">
        <h4>{{$meeting->subject->name}} - {{$meeting->subject->semester->name}} - {{$meeting->subject->classroom->academicYear->name}}</h4>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md">
    <div class="flex-1 overflow-scroll">
        <table style="width: 50rem;" class="min-w-full md:w-full">
            <thead class="bg-blue-500 text-white">
                <tr class="text-left *:py-2">
                    <th rowspan="1" class="pl-4">Keterangan</th>
                    <th rowspan="1" class="w-52">Berakhir pada</th>
                    <th rowspan="1" class="w-24">status</th>
                    <th rowspan="1" class="w-52">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- assignment -->
                @if ($assignment != null)
                    <tr class="text-left *:py-2">
                        <td class="pl-4">Tugas {{$assignment->title}}</td>
                        <td class="text-sm text-red-500">
                            {{$assignment->expired}}
                        </td>
                        <td class="text-sm *:px-2 *:py-1">
                            @switch ($assignment->status)
                                @case ('waiting')
                                    <span class="bg-gray-500 text-white rounded-sm">Dikunci</span>
                                    @break

                                @case ('active')
                                    <span class="bg-green-500 text-white rounded-sm">Dibuka</span>
                                    @break

                                @case ('ended')
                                    <span class="bg-red-500 text-white rounded-sm">Berakhir</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="/admin/assignments/{{$assignment->id}}" class="btn btn-primary">Detail</a>
                                <a href="/admin/assignments/{{$assignment->id}}/edit" class="btn btn-warning">Edit</a>
                                <form action="/admin/assignments/{{$assignment->id}}" method="post" delete-attribute="true" title-attribute="Hapus {{$assignment->title}}?" text-attribute="Jawaban dan nilai siswa akan dihapus juga">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr class="text-left *:py-2">
                        <td class="pl-4" colspan="3">Tugas</td>
                        <td class="flex items-center justify-center gap-2">
                            <a href="/admin/assignments/create?meeting={{$meeting['id']}}" class="btn btn-primary">Buat</a>
                        </td>
                    </tr>
                @endif
                <!-- quiz -->
                @if ($quiz != null)
                    <tr class="text-left *:py-2">
                        <td class="pl-4">Kuis {{$quiz->title}}</td>
                        <td class="text-sm text-red-500">
                            {{$quiz->expired}}
                        </td>
                        <td class="text-sm *:px-2 *:py-1">
                            @switch ($quiz->status)
                                @case ('waiting')
                                    <span class="bg-gray-500 text-white rounded-sm">Dikunci</span>
                                    @break

                                @case ('active')
                                    <span class="bg-green-500 text-white rounded-sm">Dibuka</span>
                                    @break

                                @case ('ended')
                                    <span class="bg-red-500 text-white rounded-sm">Berakhir</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="/admin/quiz/{{$quiz->id}}" class="btn btn-primary">Detail</a>
                                <a href="/admin/quiz/{{$quiz->id}}/edit" class="btn btn-warning">Edit</a>
                                <form action="/admin/quiz/{{$quiz->id}}" method="post" delete-attribute="true" title-attribute="Hapus {{$quiz->title}}?" text-attribute="Jawaban dan nilai siswa akan dihapus juga">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr class="text-left *:py-2">
                        <td class="pl-4" colspan="3">Kuis</td>
                        <td class="flex items-center justify-center gap-2">
                            <a href="/admin/quiz/create?meeting={{$meeting['id']}}" class="btn btn-primary">Buat</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection