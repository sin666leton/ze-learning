@extends('components.layout.admin')
@section('header')
<div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
    <div class="flex-1">
        <h2 class="text-xl">Edit postingan</h4>
    </div>
    <div class="flex-1">
        <h4>Form edit postingan</h4>
    </div>
</div>
@endsection
@section('content')
<div class="flex items-center justify-center">
    <form action="/admin/post/{{request()->id}}" method="POST" class="flex-1 w-full">
        @csrf
        @method('PUT')
        <div class="form-group">
            <textarea name="contentVal" id="content">{{$post['content']}}</textarea>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'), {
                ckfinder: {
                    uploadUrl: '/img?_token={{csrf_token()}}',
                }
            })
            .then(result => {
                console.log(result);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
@endsection