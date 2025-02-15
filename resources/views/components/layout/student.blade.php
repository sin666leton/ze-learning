@extends('components.layout.master')
@section('sidebar')
<div class="flex flex-col flex-shrink-0 justify-between gap-2 md:w-1/5 h-screen bg-white shadow-md z-30">
    <div class="flex-shrink-0 flex items-center justify-center h-14 border-b border-gray-200">
        <h2 class="text-xl font-semibold">E-Learning.</h2>
    </div>
    <ul class="flex-1 block *:*:px-4">
        <li class="block hover:bg-gray-100 duration-100">
            <a href="/students/dashboard" class="block py-3">Dashboard</a>
        </li>
        <li class="block hover:bg-gray-100 duration-100">
            <a href="/students/subjects" class="block py-3">Mata pelajaran</a>
        </li>
        <li class="block hover:bg-gray-100 duration-100">
            <a href="/students/scores" class="block py-3">Nilai</a>
        </li>
    </ul>
    <div class="flex-shrink-0 flex items-center justify-center py-2">
        <a href="/students/logout" class="py-2 w-5/6 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 duration-100">
            Logout
        </a>
    </div>
</div>
@section('navbar')
<div class="flex-shrink-0 relative flex items-center justify-between w-full bg-white shadow-md shadow-gray-100 h-14 px-4">
    <div class="flex-1">
        <div class="w-5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path fill="currentColor" d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/>
            </svg>
        </div>
    </div>
    <div class="flex-shrink-0">
        <div class="flex items-center justify-between gap-3">
            <div class="flex-1 w-40 overflow-hidden text-right">
                <h4 class="truncate">{{request()->user()->name}}</h4>
            </div>
            <div class="flex-shrink-0">
                <div class="overflow-hidden aspect-square rounded-full w-10 h-10">
                    <img src="/assets/images/default.jpg" alt="Profile picture" width="100%" height="100%">
                </div>
            </div>
        </div>
    </div>
    <!-- Profile dropdown -->
    <div class="absolute top-full right-0 w-44 bg-white shadow-md shadow-gray-100 hidden">
        <ul class="block space-y-2">
            <li class="block">
                <a href="#" class="block px-3 py-1.5 hover:bg-gray-100 duration-100">Profile</a>
            </li>
            <li class="block">
                <a href="#" class="block px-3 py-1.5 hover:bg-gray-100 duration-100">Ganti password</a>
            </li>
            <li class="block">
                <a href="#" class="block px-3 py-1.5 hover:bg-red-500 hover:text-white text-red-500 duration-100">Logout</a>
            </li>
        </ul>
    </div>
</div>
@endsection
@section('body')
@yield('header')
<div class="flex-1">
    @yield('content')
</div>
@endsection
@endsection