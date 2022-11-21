<?php

namespace App\Http\Controllers\Auth;

use Log;
use App\User;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    // 2021/12/11
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
        Log::info('ResetPasswordController redirectTo user = ' . print_r(json_decode($user),true));
        // if(! Auth::user()) {
        //      return '/';
        // }
        if($login_flg==$id_dumy)    //Client  1:顧客
            // return route('topclient', ['user' => Auth::id()]);
            return route('topclient');
        else
            // return route('top', ['user' => Auth::id()]);
            return route('top');


     }
}
