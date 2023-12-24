<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (Auth::guard('admin')->attempt($credentials, $request->get('remember'))) {

                $admin = Auth::guard('admin')->user();
                if ($admin->role == 2) {
                    return redirect()->route('admin.dashboard');
                } else {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')
                        ->with('error', 'The provided credentials do not role.');
                }

            } else {
                return redirect()->route('admin.login')
                    ->with('error', 'The provided credentials do not match our records.');
            }

        } else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
