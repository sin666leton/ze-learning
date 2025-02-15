<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Student cache
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache ttl for your application
    |
    */

    'latest_classroom' => now()->addHours(1),
    'student_subjects' => now()->addMinutes(30)
];