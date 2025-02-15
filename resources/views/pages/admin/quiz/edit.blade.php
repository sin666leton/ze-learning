@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">Tambah kuis</h4>
        </div>
        <div class="flex-1">
            <h4>/ / /</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4">
    <form action="/admin/quizzes/{{$quiz->id}}" method="post" class="form-group">
        @csrf
        @method('PUT')
        <div class="form-control">
            <label for="subject">Mata pelajaran</label>
            <input type="text" name="subject" id="" value="{{$quiz->subject->name}}" disabled>
            <input type="hidden" name="subject_id" value="{{$quiz->subject->id}}">
        </div>
        <div class="form-control">
            <label for="name">Judul</label>
            <input type="text" name="title" id="name" value="{{$quiz->title}}">
        </div>
        <div class="form-control">
            <label for="description">Deskripsi kuis</label>
            <textarea name="content" id="description">{{$quiz->content}}</textarea>
        </div>
        <div class="flex-1 form-control">
            <label for="size">Durasi pengerjaan (menit)</label>
            <input type="number" name="duration" id="size" min="1" placeholder="contoh: 10" value="{{$quiz->duration}}">
        </div>
        <div class="flex flex-col items-center justify-between md:flex-row gap-3">
            <div class="flex-1 form-control">
                <label for="access_at">Dapat diakses pada</label>
                <input type="datetime-local" name="access_at" id="access_at" value="{{$quiz->access_at}}">
            </div>
            <div class="flex-1 form-control">
                <label for="expired_at">Berakhir pada</label>
                <input type="datetime-local" name="ended_at" id="expired_at" value="{{$quiz->ended_at}}">
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