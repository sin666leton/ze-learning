<?php

namespace App\Providers;

use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Quiz;
use App\Models\Semesters;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Observers\AssignmentObserver;
use App\Observers\LogAlertEntityObserver;
use App\Observers\QuizObserver;
use App\Observers\StudentObserver;
use App\Observers\UserObserver;
use App\Repositories\AcademicYearRepository;
use App\Repositories\AnswerAssignmentRepository;
use App\Repositories\AssignmentRepository;
use App\Repositories\AttemptRepository;
use App\Repositories\ClassroomRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\QuizRepository;
use App\Repositories\SemesterRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Contracts\Classroom::class, ClassroomRepository::class);
        $this->app->bind(\App\Contracts\Assignment::class, AssignmentRepository::class);
        $this->app->bind(\App\Contracts\Quiz::class, QuizRepository::class);
        $this->app->bind(\App\Contracts\Student::class, StudentRepository::class);
        $this->app->bind(\App\Contracts\Question::class, QuestionRepository::class);
        $this->app->bind(\App\Contracts\AnswerAssignment::class, AnswerAssignmentRepository::class);
        $this->app->bind(\App\Contracts\Attempt::class, AttemptRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AcademicYear::observe(LogAlertEntityObserver::class);
        Semesters::observe(LogAlertEntityObserver::class);
        Classroom::observe(LogAlertEntityObserver::class);
        Subject::observe(LogAlertEntityObserver::class);
        Assignment::observe(AssignmentObserver::class);
        Quiz::observe(QuizObserver::class);
        Student::observe(StudentObserver::class);
        User::observe(UserObserver::class);
    }
}
