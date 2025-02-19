<?php

use App\Exceptions\AcademicYearNotExists;
use App\Exceptions\ClassroomNotExists;
use App\Exceptions\InvalidStudentCredential;
use App\Exceptions\QuizNotExists;
use App\Exceptions\SemesterNotExists;
use App\Exceptions\StudentNotExists;
use App\Exceptions\SubjectNotExists;
use App\Exceptions\UploadFileFailed;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'onAttempt' => \App\Http\Middleware\OnAttempt::class,
            'onlyTeacher' => \App\Http\Middleware\OnlyTeacher::class,
            'jsonOnly' => \App\Http\Middleware\AcceptOnlyJSONMiddleware::class,
            'onlyStudent' => \App\Http\Middleware\onlyStudent::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (\TypeError $e, Request $request) {
        //     return abort(404);
        // });

        $exceptions->render(function (InvalidStudentCredential $e, Request $request) {
            Log::channel('auth')
                ->notice("login failed", [
                    'IP' => $request->ip(),
                    'request' => $request->safe()->all(),
                    'uri' => $request->uri()->__tostring()
                ]);
            session()->flash('error', 'NIS atau password salah');
            return redirect()->to('/')->withInput(['nis' => $request->nis]);
        });

        $exceptions->render(function (ClassroomNotExists $e, Request $request)  {
            Log::channel('activity')
                ->notice("trying to access a classroom that doesn't exist", [
                    'IP' => $request->ip(),
                    'userID' => $request->user() ? $request->user()->id : 'Guest',
                    'uri' => $request->uri()->__tostring()
                ]);
            return abort(404);
        });

        $exceptions->render(function (SubjectNotExists $e, Request $request) {
            Log::channel('activity')
                ->notice("trying to access a subject that doesn't exist", [
                    'IP' => $request->ip(),
                    'userID' => $request->user() ? $request->user()->id : 'Guest',
                    'uri' => $request->uri()->__tostring()
                ]);
            return abort(404);
        });

        $exceptions->render(function (UploadFileFailed $e, Request $request) {
            if ($request->path() == '/students/answer/assignments') {
                Log::channel('activity')
                    ->emergency("Failed upload file", [
                        'IP' => $request->ip(),
                        'userID' => $request->user() ? $request->user()->id : 'Guest',
                        'request' => $request->all(),
                        'uri' => $request->uri()->__tostring()
                    ]);
                return abort(500);
            }

        });

        $exceptions->render(function (QuizNotExists $e, Request $request) {
            Log::channel('activity')
                ->notice("trying to access a quiz that doesn't exist", [
                    'IP' => $request->ip(),
                    'userID' => $request->user() ? $request->user()->id : 'Guest',
                    'uri' => $request->uri()->__tostring()
                ]);

            return abort(404);
        });

        $exceptions->render(function (AcademicYearNotExists $e, Request $request) {
            Log::channel('activity')
                ->notice("trying to access a academic year that doesn't exist", [
                    'IP' => $request->ip(),
                    'userID' => $request->user() ? $request->user()->id : 'Guest',
                    'uri' => $request->uri()->__tostring()
                ]);

            return abort(404);
        });

        $exceptions->render(function (SemesterNotExists $e, Request $request) {
            Log::channel('activity')
                ->notice("trying to access a semester that doesn't exist", [
                    'IP' => $request->ip(),
                    'userID' => $request->user() ? $request->user()->id : 'Guest',
                    'uri' => $request->uri()->__tostring()
                ]);

            return abort(404);
        });

        $exceptions->render(function (StudentNotExists $e, Request $request) {
            Log::channel('activity')
                ->notice("trying to access a student that doesn't exist", [
                    'IP' => $request->ip(),
                    'userID' => $request->user() ? $request->user()->id : 'Guest',
                    'uri' => $request->uri()->__tostring()
                ]);

            return abort(404);
        });
    })->create();
