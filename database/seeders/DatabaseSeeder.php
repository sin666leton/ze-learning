<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'profile' => '/assets/images/profile.jpg',
            'name' => 'Juned',
            'email' => 'juned@gmail.com',
            'password' => Hash::make('juned')
        ]);

        $user->teacher()->create();

        // $academicYear = AcademicYear::create([
        //     'name' => '2025/2026'
        // ]);


        // $academicYear->semesters()->create([
        //     'name' => 'Semester ganjil',
        //     'start' => '2025/1/20',
        //     'end' => '2025/1/20'
        // ]);

        // $classroom = $academicYear->classrooms()
        //     ->create([
        //         'name' => 'PPKN'
        //     ]);

        // $subject = $classroom->subjects()->create([
        //     'semester_id' => 1,
        //     'name' => 'Matematika',
        //     'kkm' => 75
        // ]);

        // $subject->assignments()->create([
        //     'title' => 'tugas',
        //     'content' => 'tugas',
        //     'size' => 10,
        //     'access_at' => '2025-02-1T23:00:00',
        //     'ended_at' => '2025-02-2T23:00:00',
        // ]);

        // $quiz = $subject->quizzes()->create([
        //     'title' => 'Kuis',
        //     'content' => 'Deskripsi kuis',
        //     'duration' => 120,
        //     'access_at' => '2025-1-29T23:00:00',
        //     'ended_at' => '2025-1-30T23:00:00',
        // ]);

        // $user = User::create([
        //     'email' => 'zidan@gmail.com',
        //     'name' => 'zidan',
        //     'password' => Hash::make('zidan')
        // ]);

        // $student = $user->student()->create([
        //     'nis' => 123
        // ]);

        // $student->classrooms()->attach(1);

        // // mcq
        // $mcq1 = $quiz->questions()->create([
        //     'content' => 'Berapa hasil dari 1+1?',
        //     'point' => 5,
        //     'type' => 'mcq'
        // ]);

        // $mcq1->choices()->create([
        //     'content' => '1'
        // ]);

        // $mcq1->answerKey()->create([
        //     'content' => '2'
        // ]);

        // $mcq1->choices()->create([
        //     'content' => '3'
        // ]);

        // $mcq1->choices()->create([
        //     'content' => '4'
        // ]);        

        
        // // essay
        // $quiz->questions()->create([
        //     'content' => 'Berapa hasil dari 10+10?',
        //     'point' => 10,
        //     'type' => 'essay'
        // ]);
    }
}
