<?php
namespace App\Services;

use App\Exceptions\InvalidStudentCredential;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function studentLogin(
        int $nis,
        string $password
    )
    {
        $student = Student::with('user')->select(['id', 'nis', 'user_id'])
            ->where('nis', $nis)
            ->firstOr(function () {
                throw new InvalidStudentCredential();
            });

        $attempt = Auth::attempt([
            'email' => $student->user->email,
            'password' => $password
        ]);

        return $attempt;
    }

    public function teacherLogin(
        string $email,
        string $password
    ): bool
    {
        $attempt = Auth::attempt([
            'email' => $email,
            'password' => $password
        ]);

        if ($attempt) {
            if (Auth::user()->teacher) {
                return true;
            } else {
                Auth::logout();
                return false;
            }
        } else {
            return false;
        }
    }

    public function logout()
    {
        Auth::logout();
    }
}