<?php

namespace App\Providers;

use App\Repositories\V1\AssignmentRepository;
use App\Repositories\V1\AcademicYearRepository;
use App\Repositories\V1\ClassroomRepository;
use App\Repositories\V1\SemesterRepository;
use App\Repositories\V1\StudentRepository;
use App\Repositories\V1\SubjectRepository;
use Illuminate\Support\ServiceProvider;

class V1ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Contracts\Student::class, StudentRepository::class);
        $this->app->bind(\App\Contracts\AcademicYear::class, AcademicYearRepository::class);
        $this->app->bind(\App\Contracts\Semester::class, SemesterRepository::class);
        $this->app->bind(\App\Contracts\Classroom::class, ClassroomRepository::class);
        $this->app->bind(\App\Contracts\Subject::class, SubjectRepository::class);
        $this->app->bind(\App\Contracts\Assignment::class, AssignmentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
