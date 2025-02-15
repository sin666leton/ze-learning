@extends('components.layout.admin')
@section('header')
<!-- <div class="flex-shrink-0 flex flex-col gap-4">
    <div class="flex-shrink-0 flex flex-col -space-y-1 p-2">
        <div class="flex-1">
            <h2 class="text-xl">{{$quiz->title}}</h4>
        </div>
        <div class="flex-1">
            <h4>{{$quiz->description}}</h4>
        </div>
    </div>
</div> -->
@endsection
@section('content')
<div class="flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md p-4 mb-4">
    <div class="flex-shrink-0">
        <h4 class="text-lg font-semibold">{{$quiz->title}}</h4>
    </div>
    <div class="flex-shrink-0">
        <p>{{$quiz->content}}</p>
    </div>
    <div class="flex-1 pb-3">
        <p>{{$quiz->description}}</p>
    </div>
    <div class="flex-shrink-0">
        <p>Durasi pengerjaan: {{$quiz->duration}} menit</p>
    </div>
</div>
<div class="flex flex-col gap-4" id="dynamicField">
    @foreach ( $quiz->questions as $question )
        <form action="/admin/questions/{{$question->id}}" attribute-method="PUT" class="flex-shrink-0 flex flex-col bg-white w-full rounded-md overflow-hidden shadow-md gap-2">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="{{$question->type}}">
            <div class="flex-shrink-0 flex items-center justify-between px-4 py-2">
                <div class="flex-1">
                </div>
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-end gap-2">
                        <div class="flex-1">
                            <label for="point">Poin</label>
                        </div>
                        <div class="flex-1">
                            <input type="number" name="point" id="point" class="px-2 py-1 rounded-md border border-gray-500 focus:outline-none focus:border-black" value="{{$question->point}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 px-4">
                <textarea name="content" id="content">{{$question->content}}</textarea>
            </div>
            @if ($question->type == 'mcq')
                <div class="flex-shrink-0 px-4 mt-4">
                    <h4 class="mb-1">Pilihan ganda</h4>
                    <div class="flex flex-col gap-2">
                        @foreach ( $question->choices as $choice )
                            <div class="flex-shrink-0">
                                <input type="text" name="choice" id="" class="border border-gray-500 px-2 py-1 rounded-md w-full" value="{{$choice->content}}">
                            </div>
                        @endforeach
                        <div class="flex-shrink-0 space-y-1 mt-1">
                            <label for="answer">Kunci jawaban</label>
                            <input type="text" name="answer" id="" class="border border-gray-500 px-2 py-1 rounded-md w-full" value="{{$question->answerKey->content}}">
                        </div>
                    </div>
                </div>
            @endif
            <div class="flex-shrink-0 mb-2 px-4 pt-5">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" target-id="{{$question->id}}" onclick="handleDelete(this)">Hapus</button>
            </div>
        </form>
    @endforeach
</div>
<div class="flex items-center justify-center mt-8 gap-4">
    <button type="button" id="handleAddMCQ" class="flex-shrink-0 flex items-center justify-center bg-blue-500 hover:bg-blue-600 duration-100 px-4 py-2.5 rounded-full text-white text-sm">Tambah soal pilihan ganda</button>
    <button type="button" id="handleAddEssay" class="flex-shrink-0 flex items-center justify-center bg-blue-500 hover:bg-blue-600 duration-100 px-4 py-2.5 rounded-full text-white text-sm">Tambah soal essay</button>
</div>
@endsection
@push('scripts')
@vite('resources/js/app.js')
<script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
<script>
    const csrfToken = '{{csrf_token()}}';

    function Editor(element, token) {
        return ClassicEditor.create(element, {
            ckfinder: {
                uploadUrl: '/admin/upload/questionImage?_token='+token
            }
        }).catch(error => {
            console.log(error)
        });
    }

    function handleCancel(element) {
        Swal.fire({
            title: "Hapus pertanyaan?",
            text: "Item tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, saya mengerti!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                element.parentNode.parentNode.remove();
            }
        });
    }

    function handleDelete(element) {
        Swal.fire({
            title: "Hapus pertanyaan?",
            text: "Item tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, saya mengerti!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                const id = element.getAttribute('target-id');

                fetch('/admin/questions/'+id, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN':csrfToken
                    },
                    method: 'DELETE',
                    credentials:"same-origin"
                }).then(result => {
                    Toast.fire({
                        icon: 'success',
                        text: 'Pertanyaan berhasil dihapus'
                    });
    
                    const form = element.parentElement.parentElement;
    
                    form.classList.remove('hidden');
                    form.classList.add('hidden');
                });

            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const fieldContainer = document.getElementById('dynamicField');
        const quiz_id = '{{$quiz->id}}';
    
        document.querySelectorAll('form').forEach(item => {
            Editor(item.querySelector('#content'), csrfToken);

            const type = item.querySelector('input[name=type]');
            const point = item.querySelector('input[name=point]');
            const content = item.querySelector('textarea[name=content]');
            const choices = item.querySelectorAll('input[name=choice]');
            const answer = item.querySelector('input[name=answer]');
            
            item.addEventListener('submit', function (event) {
                event.preventDefault();

                let data;

                if (type.value === 'mcq') {
                    data = {
                        quiz_id: quiz_id,
                        type: type.value,
                        content: content.value,
                        point: point.value,
                        choices: [...choices].map(item => {
                                    return { content: item.value };
                                }),
                        answer: answer.value
                    };
                } else {
                    data = {
                        quiz_id: quiz_id,
                        type: type.value,
                        content: content.value,
                        point: point.value
                    };
                }

                fetch(this.getAttribute('action'), {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    method: this.getAttribute('attribute-method'),
                    body: JSON.stringify(data),
                    credentials: "same-origin"
                }).then(result => {
                    Toast.fire({
                        icon: "success",
                        title: "Pertanyaan berhasil diperbarui"
                    });
                })        
            });
        });
    
        
        document.getElementById('handleAddMCQ').addEventListener('click', function (){
            const newQuestion = document.createElement('form');

            newQuestion.classList.add('flex-shrink-0', 'flex', 'flex-col', 'bg-white', 'w-full', 'rounded-md', 'overflow-hidden', 'shadow-md', 'gap-2');
            newQuestion.setAttribute('action', '/admin/questions');
            newQuestion.setAttribute('attribute-method', 'POST');

            newQuestion.innerHTML = `
                <div class="flex-shrink-0 flex items-center justify-between px-4 py-2">
                    <div class="flex-1">
                    </div>
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-end gap-2">
                            <div class="flex-1">
                                <label for="point">Poin</label>
                            </div>
                            <div class="flex-1">
                                <input type="number" name="point" id="point" class="px-2 py-1 rounded-md border border-gray-500 focus:outline-none focus:border-black" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 px-4">
                    <textarea name="content" id="content"></textarea>
                </div>
                <div class="flex-shrink-0 px-4 mt-4">
                    <h4 class="mb-1">Pilihan ganda</h4>
                    <div class="flex flex-col gap-2">
                        <input type="hidden" name="type" value="mcq">
                        <div class="flex-shrink-0">
                            <input type="text" name="choice" id="" class="border border-gray-500 px-2 py-1 rounded-md w-full" required>
                        </div>
                        <div class="flex-shrink-0">
                            <input type="text" name="choice" id="" class="border border-gray-500 px-2 py-1 rounded-md w-full" required>
                        </div>
                        <div class="flex-shrink-0">
                            <input type="text" name="choice" id="" class="border border-gray-500 px-2 py-1 rounded-md w-full" required>
                        </div>
                        <div class="flex-shrink-0 space-y-1 mt-1">
                            <label for="answer">Kunci jawaban</label>
                            <input type="text" name="answer" id="" class="border border-gray-500 px-2 py-1 rounded-md w-full" required>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 mb-2 px-4 pt-5">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger hidden" alert-attribute="true" target-id="" onclick="handleDelete(this)">Hapus</button>
                    <button type="button" class="btn btn-dead" cancel-attribute="true" target-id="" onclick="handleCancel(this)">Batal</button>
                </div>
            `;

            const fieldContent = newQuestion.querySelector('textarea');

            Editor(fieldContent, csrfToken);

            newQuestion.addEventListener('submit', function (event){
                event.preventDefault();

                const type = this.querySelector('input[name=type]');
                const point = this.querySelector('input[name=point]');
                const content = this.querySelector('textarea[name=content]');
                const choices = this.querySelectorAll('input[name=choice]');
                const answer = this.querySelector('input[name=answer]');

                const data = {
                    quiz_id: quiz_id,
                    type: type.value,
                    content: content.value,
                    point: point.value,
                    choices: [...choices].map(item => {
                                return { content: item.value };
                            }),
                    answer: answer.value
                };
                
                fetch(this.getAttribute('action'), {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                        
                    },
                    method: this.getAttribute('attribute-method'),
                    body: JSON.stringify(data),
                    credentials: "same-origin"
                }).then(result => result.json())
                .then(result => {
                    if (this.getAttribute('attribute-method') === 'PUT') {
                        Toast.fire({
                            icon: "success",
                            title: "Pertanyaan berhasil diperbarui"
                        });
                    } else {
                        Toast.fire({
                            icon: "success",
                            title: "Pertanyaan berhasil dibuat"
                        });
                        this.setAttribute('action', '/admin/questions/'+result.data);
                        this.setAttribute('attribute-method', 'PUT');

                        const handleDelete = this.querySelector('button[alert-attribute=true]');
                        const handleCancel = this.querySelector('button[cancel-attribute=true]');

                        handleDelete.setAttribute('target-id', result.data);
                        handleDelete.classList.remove('hidden');
                        handleCancel.classList.add('hidden');
                    }

                });
            });

            fieldContainer.appendChild(newQuestion);
        });
        
        document.getElementById('handleAddEssay').addEventListener('click', function (){
            const newQuestion = document.createElement('form');
    
            newQuestion.classList.add('flex-shrink-0', 'flex', 'flex-col', 'bg-white', 'w-full', 'rounded-md', 'overflow-hidden', 'shadow-md', 'gap-2');
            newQuestion.setAttribute('action', '/admin/questions');
            newQuestion.setAttribute('attribute-method', 'POST');
            newQuestion.classList.add('flex-shrink-0', 'flex', 'flex-col', 'bg-white', 'w-full', 'rounded-md', 'overflow-hidden', 'shadow-md', 'gap-2');
            newQuestion.innerHTML = `
                    <input type="hidden" name="type" value="essay">
                    <div class="flex-shrink-0 flex items-center justify-between px-4 py-2">
                    <div class="flex-1">
                    </div>
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-end gap-2">
                            <div class="flex-1">
                                <label for="point">Poin</label>
                            </div>
                            <div class="flex-1">
                                <input type="number" name="point" id="point" class="px-2 py-1 rounded-md border border-gray-500 focus:outline-none focus:border-black" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 px-4">
                    <textarea name="content" id="content"></textarea>
                </div>
                <div class="flex-shrink-0 mb-2 px-4 pt-5">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger hidden" alert-attribute="true" target-id="" onclick="handleDelete(this)">Hapus</button>
                    <button type="button" class="btn btn-dead" cancel-attribute="true" target-id="" onclick="handleCancel(this)">Batal</button>
                </div>
            `;
    
            const fieldContent = newQuestion.querySelector('textarea');
    
            Editor(fieldContent, csrfToken);
    
            newQuestion.addEventListener('submit', function (event){
                event.preventDefault();
    
                const type = this.querySelector('input[name=type]');
                const point = this.querySelector('input[name=point]');
                const content = this.querySelector('textarea[name=content]');
                const choices = this.querySelectorAll('input[name=choice]');
                const answer = this.querySelector('input[name=answer]');
    
                const data = {
                    quiz_id: quiz_id,
                    type: type.value,
                    content: content.value,
                    point: point.value
                };
                
                fetch(this.getAttribute('action'), {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    method: this.getAttribute('attribute-method'),
                    body: JSON.stringify(data),
                    credentials: "same-origin"
                }).then(result => result.json())
                .then(result => {
                    if (this.getAttribute('attribute-method') === 'PUT') {
                        Toast.fire({
                            icon: "success",
                            title: "Pertanyaan berhasil diperbarui"
                        });
                    } else {
                        Toast.fire({
                            icon: "success",
                            title: "Pertanyaan berhasil dibuat"
                        });
                        this.setAttribute('action', '/admin/questions/'+result.data);
                        this.setAttribute('attribute-method', 'PUT');
                        const handleDelete = this.querySelector('button[alert-attribute=true]');
                        const handleCancel = this.querySelector('button[cancel-attribute=true]');

                        handleDelete.setAttribute('target-id', result.data);
                        handleDelete.classList.remove('hidden');
                        handleCancel.classList.add('hidden');
                    }
                });
            });
    
            fieldContainer.appendChild(newQuestion);
        });
    });

</script>
@endpush