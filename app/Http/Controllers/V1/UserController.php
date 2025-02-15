<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\InvalidStudentCredential;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        Log::channel('auth')
            ->notice("Attempt to access student account", [
                'IP' => $request->ip(),
                'request' => $request->safe()->all(),
                'uri' => $request->uri()->__tostring()
            ]);

        $auth = $this->user->studentLogin(
            $request->safe()->nis,
            $request->safe()->password
        );

        if ($auth) {
            Log::channel('auth')
                ->info("Login successful", [
                    'IP' => $request->ip(),
                    'request' => $request->safe()->all()
                ]);
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
        
        Log::channel('auth')
            ->info("Logout", [
                'IP' => $request->ip(),
                'userID' => $request->user()->id,
                'Role' => $loginView == '/admin' ? 'Teacher' : 'Student'
            ]);

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
