<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyExamToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Authorization token missing'], 401);
        }

        $secretKey = config('token.INTEGRATION_TOKEN_SECRET');

        try {
            $examCategory = $this->decryptToken($token, $secretKey);

            $request->merge(['exam_name' => $examCategory]);

        } catch (\Exception $e) {
            Log::error('Token decryption failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid or expired token'], 400);
        }

        return $next($request);
    }

    /**
     * Decrypt the token using the secret key
     *
     * @param  string  $token
     * @param  string  $secretKey
     * @return string
     */
    private function decryptToken($token, $secretKey)
    {
        $decodedToken = base64_decode($token);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($decodedToken, 0, $ivLength);
        $encryptedData = substr($decodedToken, $ivLength);

        $decrypted = openssl_decrypt($encryptedData, 'aes-256-cbc', $secretKey, 0, $iv);

        if ($decrypted === false) {
            throw new \Exception('Decryption failed');
        }

        return $decrypted;
    }
}
