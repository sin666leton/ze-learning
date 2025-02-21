<?php

use App\Http\Controllers\V1\AcademicYearController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AnswerQuizController;
use App\Http\Controllers\V1\AssignmentController;
use App\Http\Controllers\V1\ClassroomController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\V1\QuizController;
use App\Http\Controllers\V1\SemesterController;
use App\Http\Controllers\V1\ScoreController;
use App\Http\Controllers\V1\StudentController;
use App\Http\Controllers\V1\StudentManagementController;
use App\Http\Controllers\V1\SubjectController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('/admin', 'adminLoginView');
    Route::post('/admin', 'adminLogin');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/', 'studentLoginView');
    Route::post('/students', 'studentLogin');
});

Route::prefix('admin')->middleware('onlyTeacher')->group(function () {
    Route::get('/dashboard', [UserController::class, 'adminDashboardView']);
    Route::get('/logout', [UserController::class, 'logout']);

    Route::resource('academic-years', AcademicYearController::class)->except('show');
    Route::resource('semesters', SemesterController::class)->except('show');
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('subjects', SubjectController::class)->except('index');
    Route::resource('assignments', AssignmentController::class);
    Route::resource('quizzes', QuizController::class);
    Route::resource('students', StudentManagementController::class);
    Route::resource('scores', ScoreController::class)->only(['edit', 'update']);

    Route::prefix('student-management')->controller(StudentManagementController::class)->group(function() {
        Route::post('/search', 'findStudent')->middleware('jsonOnly');
        Route::post('/classrooms', 'addClassroom');
        Route::delete('/classrooms/{student_id}/{classroom_id}', 'removeClassroom');
    });

    Route::apiResource('questions', QuestionController::class);

    Route::get('/answer_quiz/{id}', [AnswerQuizController::class, 'show']);
    Route::post('/upload/questionImage', [FileUploadController::class, 'questionImage']);

    Route::controller(ExportController::class)->group(function () {
        Route::get('/export/assignment/score/{id}', 'assignmentScore');
    });
});

Route::prefix('students')->middleware('onlyStudent')->controller(StudentController::class)->group(function () {
    Route::get('/dashboard', 'dashboard');
    Route::get('/subjects', 'subjectView');
    Route::get('/subjects/{id}', 'subjectRead');
    Route::get('/assignments/{id}', 'assignmentRead');
    Route::get('/quizzes/questions', 'questionView');
    Route::get('/quizzes/{id}', 'quizRead');
    Route::get('/scores', 'scoreView');

    Route::post('/attempt/quiz', 'attemptQuiz')->middleware(['onAttempt']);
    Route::delete('/attempt/quiz/{id}/', 'removeAttemptQuiz');

    Route::post('/answer/assignments', 'answerAssignment');
    Route::post('/answer/questions', 'answerQuestion');

    Route::get('/logout', [UserController::class, 'logout']);
});

