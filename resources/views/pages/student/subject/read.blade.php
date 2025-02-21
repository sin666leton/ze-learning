@extends('components.layout.student')
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
<div class="grid grid-cols-1 gap-2">
    @foreach ( $subject['items'] as $item )
        <div class="flex flex-col bg-white p-4 shadow-md rounded-md gap-6">
            <div class="flex-shrink-0 flex items-center justify-between">
                <div class="flex-1">
                    <h4 class="text-gray-500">{{$item['type']}}</h4>
                </div>
                <div class="flex-shrink-0">
                    <h4 class="text-gray-500">{{$item['created']}}</h4>
                </div>
            </div>
            <div class="flex-1 flex flex-col">
                <div class="flex-shrink-0 overflow-hidden">
                    <h1 class="truncate text-lg font-semibold">{{$item['title']}}</h1>
                </div>
                <div class="flex-shrink-0 overflow-hidden">
                    <p class="truncate">{{$item['content']}}</p>
                </div>
            </div>
            <div class="flex-shrink-0 mt-3">
                <a href="{{$item['link']}}" class="btn btn-primary px-4">Lihat</a>
            </div>
        </div>
    @endforeach
</div>
@endsection