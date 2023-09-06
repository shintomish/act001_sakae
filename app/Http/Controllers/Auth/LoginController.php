<?php

namespace App\Http\Controllers\Auth;

use Log;
use App\User;

use App\Models\Operation;
use App\Http\Controllers\Controller;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Operationを更新する
        $ret  = $this->update(1,$user->id);        

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

        // Operationを更新する
        $ret  = $this->update(2,$user->id);        

        // return $this->originalLogout($request); // 元々のログアウト

        // 2022/11/01 以下追加
        $actlog = new \App\Http\Middleware\ActlogMiddleware;
        $actlog -> actlog($request, 999);

        $this->guard()->logout();

        $request->session()->invalidate();

        // return $this->loggedOut($request) ?: redirect('/');
        return $this->originalLogout($request) ?: redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($sw ,$id)
    {
        Log::info('loginout operation update START');

        if($id < 10){
                Log::info('loginout operation update $id < 10 END');
            return;
        }

        $operation = Operation::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - loginout operation update start');
        try {

                if($sw == 1){
                    $operation->status_flg           = 1;
                    $operation->login_verified_at    = now();
                    // $operation->logout_verified_at   = null;
                } else {
                    $operation->status_flg           = 2;
                    // $operation->login_verified_at    = null;
                    $operation->logout_verified_at   = now();
                }

                $operation->updated_at           = now();
                $result = $operation->save();

                // Log::debug('operation update = ' . $operation);

                DB::commit();
                Log::info('beginTransaction - loginout operation update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - loginout operation update end(rollback)');
        }

        Log::info('loginout operation update END');

        return;
    }
}
