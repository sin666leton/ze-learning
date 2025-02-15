@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Tambah tugas</h4>
        </div>
        <div class="flex-1">
            <h4>/ a / b / c /</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4">
    <form action="/admin/assignments/{{$assignment->id}}" method="post" class="form-group">
        @csrf
        @method('PUT')
        <div class="form-control">
            <label for="subject">Mata pelajaran</label>
            <input type="text" name="subject" value="{{$assignment->subject->name}}" disabled>
        </div>
        <div class="form-control">
            <label for="name">Judul</label>
            <input type="text" name="title" id="name" value="{{$assignment->title}}">
        </div>
        <div class="form-control">
            <label for="description">Deskripsi tugas</label>
            <textarea name="content" id="description">{{$assignment->content}}</textarea>
        </div>
        <div class="flex flex-col items-center justify-between md:flex-row gap-3">
            <div class="flex-1 form-control">
                <label for="size">Maks ukuran file (MB)</label>
                <input type="number" name="size" id="size" min="1" placeholder="contoh: 10" value="{{$assignment->size}}">
            </div>
        </div>
        <div class="flex flex-col items-center justify-between md:flex-row gap-3">
            <div class="flex-1 form-control">
                <label for="access_at">Dapat diakses pada</label>
                <input type="datetime-local" name="access_at" id="access_at" value="{{$assignment->access_at}}">
            </div>
            <div class="flex-1 form-control">
                <label for="expired_at">Berakhir pada</label>
                <input type="datetime-local" name="ended_at" id="expired_at" value="{{$assignment->ended_at}}">
            </div>
        </div>
        <div class="flex gap-2">
            <input type="hidden" name="now" id="" value="0">
            <input type="checkbox" name="now" id="now" value="1">
            <label for="now">Sekarang</label>
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkBox = document.getElementById('now');
            const accessAtField = document.getElementById('access_at');

            function determineNowChecked() {
                if (checkBox.checked) {
                    accessAtField.setAttribute('disabled', 'true');
                    accessAtField.classList.add('bg-gray-100');
                } else {
                    accessAtField.removeAttribute('disabled');
                    accessAtField.classList.remove('bg-gray-100');
                }
            }

            determineNowChecked();

            checkBox.addEventListener('change', function () {
                determineNowChecked();
            });
        });
    </script>
@endpush
@endsection