<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TokenAuth
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        // Memeriksa validitas token atau kondisi lain yang sesuai dengan kebutuhan Anda
        if ($this->isValidToken($token)) {
            // Autentikasi pengguna berdasarkan token
            Auth::loginUsingId($this->getUserIdByToken($token));
        } else {
            // Jika token tidak valid, Anda dapat melakukan tindakan sesuai kebutuhan Anda,
            // misalnya, memberikan respons error atau meneruskan pengguna ke rute lainnya.
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

    // Logika untuk memvalidasi token
    private function isValidToken($token)
    {
        // Implementasikan logika validasi token sesuai kebutuhan Anda
        // Misalnya, validasi token menggunakan model atau layanan tertentu
        return true;
    }

    // Logika untuk mendapatkan ID pengguna berdasarkan token
    private function getUserIdByToken($token)
    {
        // Implementasikan logika untuk mendapatkan ID pengguna berdasarkan token
        // Misalnya, menggunakan model atau layanan tertentu
        return 1; // Contoh: ID pengguna 1
    }
}