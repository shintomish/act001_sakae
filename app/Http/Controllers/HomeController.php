<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();

        Log::info('HomeController index START');
        Log::info('HomeController index $user->name = ' . print_r($user->name ,true));
        Log::info('HomeController index END');

        // return view('home');
        return response(view('home'))
            ->withHeaders([
                'Cache-Control' => 'no-store',
            ]);
    }
}
