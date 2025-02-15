<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidStudentCredential;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        protected UserService $user
        ) {}

    // admin
    public function adminLoginView()
    {
        return view('pages.login.admin');
    }

    public function adminLogin(LoginRequest $request)
    {
        $status = $this->user->teacherLogin(
            $request->safe()->email,
            $request->safe()->password
        );

        if ($status) {
            session()->flash('success', 'Berhasil login');
            return redirect()->to('/admin/dashboard');
        } else {
            session()->flash('error', 'Email atau Password salah');
            return redirect()->to('/admin');
        }
    }

    public function studentLoginView()
    {
        return view('pages.login.student');
    }

    public function studentLogin(LoginRequest $request)
    {
        $auth = $this->user->studentLogin(
            $request->safe()->nis,
            $request->safe()->password
        );

        if ($auth) {
            session()->flash('success', 'Berhasil login');
            return redirect()->to('/students/dashboard');
        } else {
            throw new InvalidStudentCredential();
        }
    }

    public function logout(Request $request)
    {
        $loginView = match ($request->user()->teacher()->exists()) {
            true => '/admin',
            false => '/',
        };
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->to($loginView)->with('success', 'Berhasil logout');
    }

    public function adminDashboardView()
    {
        return view('pages.admin.dashboard');
    }
}
