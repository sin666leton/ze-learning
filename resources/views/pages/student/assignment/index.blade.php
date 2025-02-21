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
<div class="flex flex-col gap-2">
    <div class="flex-shrink-0 flex flex-col bg-white p-4 shadow-md rounded-md gap-5">
        <div class="flex-shrink-0 flex items-center justify-between">
            <div class="flex-1">
                <h4 class="text-gray-500">Tugas</h4>
            </div>
            <div class="flex-shrink-0">
                @if ($assignment['stat'] == 'Dibuka')
                    <h4 class="text-gray-500">
                        Berakhir pada {{$assignment['ended_at']}}
                    </h4>
                @elseif ($assignment['stat'] == 'Selesai')
                    <h4 class="text-red-500">
                        Telah berakhir
                    </h4>
                @endif
            </div>
        </div>
        <div class="flex-shrink-0 flex flex-col">
            <div class="flex-shrink-0">
                <h1 class="text-lg font-semibold">{{$assignment['title']}}</h1>
            </div>
            <div class="flex-shrink-0">
                <p>{{$assignment['content']}}</p>
            </div>
        </div>
    </div>
    @if ($exists)
        <div class="flex-shrink-0 flex flex-col bg-white p-4 shadow-md rounded-md gap-5">
            <div class="flex-shrink-0 flex items-center">
                <span class="text-gray-500">Kamu telah mengumpulkan tugas</span>
            </div>
            <div class="flex-1">
                File: <a target="_blank" href="{{$exists['link']}}" class="text-blue-600 hover:text-blue-700 underline">{{$exists['namespace']}}</a>
            </div>
        </div>
    @else
        <div class="flex-shrink-0 flex flex-col bg-white p-4 shadow-md rounded-md gap-5">
            <h4 class="text-lg">Form upload tugas</h4>
            <form id="uploadForm" action="/students/answer/assignments" method="POST" enctype="multipart/form-data" class="form-group">
                @csrf
                <input type="hidden" name="assignment_id" value="{{$assignment['id']}}">
                <label for="file">Pilih file atau drag file ke area ini</label>
                <div id="dropArea" style="height: 150px; border: 2px dashed #007bff; display: flex; justify-content: center; align-items: center;">
                    <p>Drag and Drop File di sini</p>
                    <input type="file" id="file" name="file" accept=".pdf" style="display: none;" required/>
                </div>
                <div class="form-control">
                    <label for="namespace">Simpan file sebagai</label>
                    <input type="text" name="namespace" maxlength="50" id="namespace">
                </div>
                <div id="fileInfo" style="margin-top: 10px; display: none;">
                    <p><span id="fileName"></span> (<span id="fileSize"></span> bytes)</p>
                </div>
                <div class="flex flex-col text-gray-500">
                    <div class="flex-shrink-0">
                        <p>Format file: .pdf</p>
                    </div>
                    <div class="flex-shrink-0">
                        <p>Maksimal: {{$assignment['size']}} MB</p>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary mt-1">Upload</button>
                </div>
            </form>
        </div>
        @push('scripts')
        <script>
            // Ambil elemen drop area dan input file
            var dropArea = document.getElementById('dropArea');
            var fileInput = document.getElementById('file');
            var fileInfo = document.getElementById('fileInfo');
            var fileName = document.getElementById('fileName');
            var fileSize = document.getElementById('fileSize');
        
            // Saat file dijatuhkan di area drop
            dropArea.addEventListener('dragover', function(event) {
                event.preventDefault(); // Menjaga agar file bisa di-drag ke area
                dropArea.style.backgroundColor = "#e9ecef"; // Ganti warna saat drag
            });
        
            dropArea.addEventListener('dragleave', function(event) {
                dropArea.style.backgroundColor = ""; // Reset warna saat drag keluar
            });
        
            dropArea.addEventListener('drop', function(event) {
                event.preventDefault();
                dropArea.style.backgroundColor = ""; // Reset warna saat drop
        
                // Ambil file yang di-drop
                var files = event.dataTransfer.files;
                if (files.length > 0) {
                    // Set file ke input file secara otomatis
                    fileInput.files = files;
        
                    // Tampilkan informasi file
                    var selectedFile = files[0];
                    fileName.textContent = selectedFile.name;
                    fileSize.textContent = selectedFile.size; // Ukuran file dalam byte
                    fileInfo.style.display = 'block'; // Tampilkan informasi file
                }
            });
        
            // Klik area untuk memilih file (opsional)
            dropArea.addEventListener('click', function() {
                fileInput.click();
            });
        
            // Jika file dipilih melalui input file (bukan drag and drop)
            fileInput.addEventListener('change', function() {
                var files = fileInput.files;
                if (files.length > 0) {
                    var selectedFile = files[0];
                    fileName.textContent = selectedFile.name;
                    fileSize.textContent = selectedFile.size; // Ukuran file dalam byte
                    fileInfo.style.display = 'block'; // Tampilkan informasi file
                }
            });
        </script>
        @endpush
    @endif
</div>
@endsection