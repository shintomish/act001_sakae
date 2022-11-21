<?php

namespace App\Http\Controllers\Auth;

use Log;
use App\User;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use AuthenticatesUsers { logout as originalLogout; }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo() {

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        // login_flg 1:顧客  2:社員  3:所属
        $login_flg = $user->login_flg;
        $id_dumy = 1;   // 1:顧客

        // Log::info(
        //  ' id:'.        $user->id.
        //  ' name:'.      $user->name.
        //  ' user_id:'.   $user->user_id.
        //  ' login_flg:'. $user->login_flg.
        //  ' email:'.     $user->email
        // );
        Log::info('auth login redirectTo user = ' . print_r(json_decode($user),true));
        if(! Auth::user()) {
            return '/';
        }

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.session_login'));

        if($login_flg==$id_dumy) {   //Client  1:顧客
            // return route('topclient', ['user' => Auth::id()]);
            return route('topclient');
        } else {
            // return route('top', ['user' => Auth::id()]);
            return route('top');
        }


    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        Log::info('auth logout redirectTo user = ' . print_r(json_decode($user),true));

        // return $this->originalLogout($request); // 元々のログアウト

        // 2022/11/01 以下追加
        $actlog = new \App\Http\Middleware\ActlogMiddleware;
        $actlog -> actlog($request, 999);

        $this->guard()->logout();

        $request->session()->invalidate();

        // return $this->loggedOut($request) ?: redirect('/');
        return $this->originalLogout($request) ?: redirect('/');
    }
}
