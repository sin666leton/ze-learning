<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Learning</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        * {
            font-family: 'Poppins';
        }
    </style>
</head>
<body class="overflow-x-hidden">
    <div class="flex flex-row h-screen w-full">
        @yield('sidebar')
        
        <!-- main content -->
        <div class="flex-1 flex flex-col w-full h-full overflow-y-scroll">
            @yield('navbar')
            

            <!-- content -->
            <div class="flex-1 flex flex-col gap-4 p-4">
                @yield('body')                
            </div>

            <!-- footer -->
            <div class="flex-shrink-0 flex items-center justify-center bg-white border-t border-gray-200 py-4">
                <h4 class="text-gray-500 text-sm">Copyright &copy; Zidan 2024</h4>
            </div>
        </div>
    </div>
    @vite('resources/js/app.js')
    <script defer>
        const hasError = '{{session()->has("error")}}';
        const hasSuccess = '{{session()->has("success")}}';

        document.addEventListener('DOMContentLoaded', function () {
            if (hasError) {
                Toast.fire({
                    title: '{{session()->get("error")}}',
                    icon: 'error'
                });
            }

            if (hasSuccess) {
                Toast.fire({
                    title: '{{session()->get("success")}}',
                    icon: 'success'
                });
            }
        });

        // implements alert when delete content
        const formMethodDelete = document.querySelectorAll('form[delete-attribute=true]');

        if (formMethodDelete && formMethodDelete.length > 0) {
            formMethodDelete.forEach(item => {
                item.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const title = item.getAttribute('title-attribute');
                    const text = item.getAttribute('text-attribute');

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Saya mengerti",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.submit();
                        }
                    });
                });
            });
        }
    </script>
    @stack('scripts')
</body>
</html>