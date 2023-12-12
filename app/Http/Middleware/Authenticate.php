<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $token = $request->query('token');
        $parts = explode('|', $token);
        $firstPart = $parts[0];
        $secondPart = $parts[1];
        // Lakukan validasi token sesuai dengan logika aplikasi Anda
       // DB::connection()->enableQueryLog();
        $accessTokens = DB::table('personal_access_tokens')
        ->select(DB::raw("id AS concatenated_value, tokenable_id"))
        ->where('token', DB::raw("SHA2('$secondPart', 256)"))
        ->where('id', $firstPart)
        ->get();
        //$queries = DB::getQueryLog();
        $concatenatedValue = @$accessTokens->first()->concatenated_value;
        $tokenableId = @$accessTokens->first()->tokenable_id;
       
        if (Auth::guard('web')->loginUsingId($tokenableId) || $request->expectsJson()) {
            // Pengguna sudah login, tampilkan halaman yang dimuat dalam iframe
            if(Auth::guard('web')->loginUsingId($tokenableId)){
                $data = '';
                $url = app('request');
                $data = $url->GetpathInfo();
                
                
            }
        } else {
            return route('login');
        }
        }
    
}
