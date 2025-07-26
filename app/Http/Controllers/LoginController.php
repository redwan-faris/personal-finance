<?php

namespace App\Http\Controllers;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Get logIn Credentials for API
     *
     * @param array $validatedData
     * @return array
     */
    private function getCredentials(array $validatedData)
    {
        $keyName = filter_var($validatedData['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        $formattedUsername = $keyName === 'phone_number'
                            ? PhoneHelper::format($validatedData['username'])
                            : $validatedData['username'];
        return [
            $keyName => $formattedUsername,
            "password" => $validatedData['password'],
            'is_active' => 1
        ];
    }

    /**
     * Login User for API
     */
    public function __invoke(LoginRequest $request)
    {
        $credentials = $this->getCredentials($request->validated());
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return success($user, 'Login Successfully',201,[
                "access_token" => $user->createToken("Access Token Api")->plainTextToken
            ]);
        }

        return error(
            'Invalid Credentials',
            401
        );
    }
}
