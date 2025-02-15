<?php

namespace App\Providers;

use App\Repositories\V1\StudentRepository;
use Illuminate\Support\ServiceProvider;

class V1ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Contracts\Student::class, StudentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
