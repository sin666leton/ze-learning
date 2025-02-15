@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Kelas {{$classroom->name}}</h4>
        </div>
        <div class="flex-1">
            <h4>Tahun ajaran {{$classroom->academicYear->name}}</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div id="floatingForm" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3 p-6 transform transition-all duration-300 opacity-0 scale-95">
        <!-- Tombol tutup -->
        <button id="closeFormBtn" class="float-right text-gray-500 hover:text-gray-700">
            &times;
        </button>
        <!-- Konten form -->
        <h2 class="text-xl font-bold mb-4">Tambah siswa</h2>
        <form method="POST" action="/admin/student-management/classrooms" id="floatingForm">
            @csrf
            <input type="hidden" name="student_id">
            <input type="hidden" name="classroom_id" value="{{$classroom->id}}">
            <div class="relative mb-4">
                <label for="nis" class="block text-sm font-medium text-gray-700">Nomor induk siswa:</label>
                <div class="flex items-center">
                    <input type="text" id="inputNIS" name="name" placeholder="NIS" class="flex-1 mt-1 block w-full px-3 py-2 border border-gray-300 rounded-s-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" id="searchResource" class="flex-shrink-0 p-3 mt-1 rounded-e-md hover:bg-blue-600 cursor-pointer duration-100 text-white border border-blue-500 bg-blue-500 h-full">
                        <svg class="w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path fill="currentColor" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                        </svg>
                    </button>
                </div>
                <ul class="hidden absolute bg-white rounded-sm shadow-md w-full border border-gray-300 *:border-b *:border-gray-400 *:py-2 *:px-3" id="resourceWrapper">
                    <li class="hidden hover:bg-gray-50 duration-100 cursor-pointer" id="resourceFound">
                        <div class="flex flex-col">
                            <div class="flex-shrink-0">
                                <h4 class="text-lg" id="resourceName"></h4>
                            </div>
                            <div class="flex-shrink-0">
                                <h4 class="text-gray-500" id="resourceNIS"></h4>
                            </div>
                        </div>
                    </li>
                    <li class="hidden text-gray-500" id="resourceNotFound"></li>
                    <li class="hidden text-gray-500" id="loadingResource">
                        <div class="flex items-center gap-2">
                            <div class="flex-shrink-0">
                                <svg class="w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                Loading
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="mb-4 hidden">
                <span class="cursor-pointer text-sm bg-gray-300 px-2 py-1 rounded-md" id="listStudent"></span>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Selesai
            </button>
        </form>
    </div>
</div>
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
                        <h4>{{$totalSubject}}</h4>
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
                        <h4>{{$totalStudent}} orang</h4>
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
                    <a href="#" id="openFormBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md duration-100 cursor-pointer">Tambah</a href="/admin/subject/create">
                    <span class="bg-blue-100 border border-blue-500 text-blue-500 py-1 px-2 rounded-md">Total {{$totalStudent}}</span>
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
                        <th rowspan="1">Nama</th>
                        <th rowspan="1" class="w-36">NIS</th>
                        <th rowspan="1" class="w-46">Bergabung pada</th>
                        <th rowspan="1" class="w-52">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $students as $student )
                        <tr class="text-left *:py-2">
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>
                                <div class="flex flex-col">
                                    <h4 class="flex-shrink-0">{{$student->user->name}}</h4>
                                    <p class="text-gray-600">{{$student->user->email}}</p>
                                </div>
                            </td>
                            <td>
                                {{$student->nis}}
                            </td>
                            <td>
                                {{$student->created_at->format('d-m-Y h:i')}}
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <a href="/admin/students/{{$student->id}}" class="btn btn-primary">Lihat</a>
                                    <form action="/admin/student-management/classrooms/{{$student->id}}/{{$classroom->id}}" method="post" delete-attribute="true" title-attribute="Hapus {{$student->user->name}} dari kelas?" text-attribute="Item tidak dapat dikembalikan!">
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
@push('scripts')
<script>
    // JavaScript untuk menangani animasi dan toggle form
    const classroomID = {{$classroom->id}};
    const openFormBtn = document.getElementById('openFormBtn');
    const closeFormBtn = document.getElementById('closeFormBtn');
    const floatingForm = document.getElementById('floatingForm');
    const modalContent = floatingForm.querySelector('div');

    // Fungsi untuk membuka form
    openFormBtn.addEventListener('click', () => {
      floatingForm.classList.remove('hidden');
      floatingForm.classList.add('flex');
      setTimeout(() => {
        modalContent.classList.remove('opacity-0', 'scale-95');
        modalContent.classList.add('opacity-100', 'scale-100');
      }, 10);
    });

    // Fungsi untuk menutup form
    closeFormBtn.addEventListener('click', () => {
      modalContent.classList.remove('opacity-100', 'scale-100');
      modalContent.classList.add('opacity-0', 'scale-95');
      setTimeout(() => {
        floatingForm.classList.add('hidden');
      }, 300); // Sesuaikan dengan durasi animasi
    });

    // Tutup form saat mengklik di luar modal
    floatingForm.addEventListener('click', (e) => {
      if (e.target === floatingForm) {
        modalContent.classList.remove('opacity-100', 'scale-100');
        modalContent.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
          floatingForm.classList.add('hidden');
        }, 300); // Sesuaikan dengan durasi animasi
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector("meta[name=csrf-token]").getAttribute('content');
        const loadingResource = document.getElementById('loadingResource');
        const resourceFound = document.getElementById('resourceFound');
        const resourceWrapper = document.getElementById('resourceWrapper');
        const resourceNotFound = document.getElementById('resourceNotFound');
        const searchResource = document.getElementById('searchResource');
        const inputNIS = document.getElementById('inputNIS');

        const listStudent = document.getElementById('listStudent');

        let tempData = {
            student_id: 0,
            classroom_id: classroomID,
            nis: 0,
            name: undefined
        };

        searchResource.addEventListener('click', async function () {
            try {
                tempData = {
                    student_id: 0,
                    classroom_id: classroomID
                };

                resourceNotFound.classList.add('hidden');
                resourceFound.classList.add('hidden');

                searchResource.setAttribute('disabled', 'true');
                searchResource.classList.remove('cursor-pointer');
                searchResource.classList.remove('hover:bg-blue-600');
                searchResource.classList.add('cursor-not-allowed');

                resourceWrapper.classList.remove('hidden');
                resourceWrapper.classList.add('block');

                loadingResource.classList.remove('hidden');

                const DName = document.getElementById('resourceName');
                const DNIS = document.getElementById('resourceNIS');

                const nis = inputNIS.value;

                console.log(nis);
                console.log(classroomID);

                const request = await fetch('/admin/student-management/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        'classroomID': classroomID,
                        'nis': nis
                    }),
                    credentials: "same-origin"
                });

                switch (request.status) {
                    case 200:
                        searchResource.removeAttribute('disabled');
                        searchResource.classList.remove('cursor-not-allowed');
                        searchResource.classList.add('cursor-pointer');
                        searchResource.classList.add('hover:bg-blue-600');
                        const result = await request.json();

                        loadingResource.classList.add('hidden');
                        resourceFound.classList.remove('hidden');

                        DName.textContent = result.data.name;
                        DNIS.textContent = result.data.nis;

                        tempData = {
                            student_id: result.data.id,
                            classroom_id:classroomID,
                            nis: result.data.nis,
                            name: result.data.name
                        };

                        break;

                    case 404:
                        searchResource.removeAttribute('disabled');
                        searchResource.classList.remove('cursor-not-allowed');
                        searchResource.classList.add('cursor-pointer');
                        searchResource.classList.add('hover:bg-blue-600');
                        loadingResource.classList.add('hidden');
                        resourceNotFound.classList.remove('hidden');

                        resourceNotFound.textContent = `${nis} tidak ditemukan`
                        break;

                    default:
                        break;
                }
            } catch (error) {

            }
        });

        resourceNotFound.addEventListener('click', function () {
            resourceWrapper.classList.add('hidden');

            tempData = {
                classroom_id: 0,
                student_id: 0,
                nis: 0
            };

            listStudent.parentElement.classList.add('hidden');
        });

        resourceFound.addEventListener('click', function () {
            resourceWrapper.classList.add('hidden');

            if (tempData.student_id !== 0) {
                listStudent.parentElement.classList.remove('hidden');
                listStudent.textContent = `${tempData.name} - ${tempData.nis}`;
            }
        });

        listStudent.addEventListener('click', function () {
            tempData = {
                student_id: 0,
                name: undefined,
                classroom_id: 0,
                nis: 0
            };

            listStudent.parentElement.classList.add('hidden');
        });

        document.getElementById('floatingForm').addEventListener('submit', function (event) {
            event.preventDefault();

            if (tempData.student_id !== 0) {
                document.querySelector('input[name=student_id]').value =tempData.student_id;
                event.target.submit();
            }
        });
    });

</script>
@endpush
@endsection
