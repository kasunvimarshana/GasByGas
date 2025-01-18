<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AuthService\AuthService;
use Exception;

class AuthController extends Controller {
    protected AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * Handle login request.
     */
    public function login(Request $request) {
        $rules = array(
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:3'
        );

        // $validated = $request->validate($rules);

        $validator = Validator::make($request->all(), $rules);

        if( $validator->fails() ) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->except(['password']));
        }

        try {
            $this->authService->login($request->only('email', 'password'), 'email', $request->remember ?? false, 'web');
            return redirect()->intended('/');
        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }

    /**
     * Handle logout request.
     */
    public function logout() {
        $this->authService->logout();
        return redirect()->route('login', []);
    }

    /**
     * Handle send reset link request.
     */
    public function sendResetLink(Request $request) {
        $rules = array(
            'email' => 'required|email|exists:users,email'
        );

        // $validated = $request->validate($rules);

        $validator = Validator::make($request->all(), $rules);

        if( $validator->fails() ) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->authService->sendResetLink($request->email);
            return back()->with('status', trans('messages.reset_password_link_sent', ['email' => $request->email]));
        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(Request $request) {
        $rules = array(
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required',
        );

        // $validated = $request->validate($rules);

        $validator = Validator::make($request->all(), $rules);

        if( $validator->fails() ) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->authService->resetPassword($request->only('email', 'password', 'password_confirmation', 'token'));
            return redirect()->route('login', [])->with('status', trans('messages.password_changed', []));
        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }
}
