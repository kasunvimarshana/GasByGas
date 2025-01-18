<?php

namespace App\Services\AuthService;

// use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Exception;

class AuthService {
    /**
     * Retrieve user by specific field dynamically.
     */
    public function findByField(string $field, string $value, ?string $guard = 'web') {
        /*
        $provider = Config::get("auth.guards.{$guard}.provider", 'users');
        $model = Config::get("auth.providers.{$provider}.model");
        $user = $model::where($field, $value)->first();
        */

        $user = Auth::guard($guard)->getProvider()->retrieveByCredentials([$field => $value]);
        if (!$user) {
            throw new Exception(trans('messages.user_not_found', ['field' => $field, 'value' => $value]));
        }
        return $user;
    }

    /**
     * Handle user login.
     */
    public function login(array $credentials, string $field, bool $remember = false, ?string $guard = 'web'): bool {
        try {
            $user = $this->findByField($field, $credentials[$field], $guard);

            if (Hash::check($credentials['password'], $user->password)) {
                Auth::guard($guard)->login($user, $remember);
                return true;
            }

            throw new Exception(trans('messages.invalid_credentials'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Handle user logout.
     */
    public function logout(?string $guard = 'web'): void {
        Auth::guard($guard)->logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * Send password reset link to user.
     */
    public function sendResetLink(string $email, ?string $guard = 'web'): bool {
        $broker = $this->getPasswordBroker($guard);

        $status = Password::broker($broker)->sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new Exception(trans($status));
        }

        return true;
    }

    /**
     * Reset user password.
     */
    public function resetPassword(array $data, ?string $guard = 'web'): bool {
        $broker = $this->getPasswordBroker($guard);

        $status = Password::broker($broker)->reset(
            $data,
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new Exception(trans($status));
        }

        return true;
    }

    /**
     * Get the password broker for a specific guard.
     */
    private function getPasswordBroker(?string $guard = 'web'): string {
        $provider = config("auth.guards.{$guard}.provider", 'users');
        return config("auth.passwords.{$provider}.provider", 'users');
    }
}
