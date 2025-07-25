<?php

namespace App\Http\Controllers;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    /**
     * Get logIn Credentials
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
     * Login User
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
