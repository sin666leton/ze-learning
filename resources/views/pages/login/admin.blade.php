<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        * {
            font-family: 'Poppins';
        }
    </style>
</head>
<body>
    <div class="flex items-center justify-center w-full h-screen bg-gray-50">
        <div class="flex-shrink-0 flex flex-col justify-between bg-white rounded-md p-8 shadow-md shadow-gray-200">
            <div class="flex flex-col items-center justify-center pb-8">
                <div class="flex-shrink-0">
                    <h1 class="text-xl font-semibold">E-learning</h1>
                </div>
                <div class="flex-1">
                    <h4>Login Admin</h4>
                </div>
            </div>
            <form action="/admin" method="POST" class="flex flex-col items-center justify-between form-group">
                @csrf
                <div class="form-control">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="" required>
                </div>
                <div class="form-control">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="" required>
                </div>
                <div class="form-control w-full mt-8">
                    <button type="submit" class="btn btn-primary py-2.5">Login</button>
                </div>
            </form>
        </div>
    </div>
    @vite('resources/js/app.js')
    <script defer>
        const hasError = '{{session()->has("error")}}';
        document.addEventListener('DOMContentLoaded', function () {
            if (hasError) {
                Toast.fire({
                    text: '{{session()->get("error")}}',
                    icon: 'error'
                });
            }
        });
    </script>
</body>
</html>