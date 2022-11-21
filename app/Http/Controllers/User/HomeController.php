<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    //コンストラクタ
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        return view('user.home');

    }
}
