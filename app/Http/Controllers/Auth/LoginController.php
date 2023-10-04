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

        // Operationを更新する 2023/09/06
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

        // Operationを更新する 2023/09/06
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
     * 2023/09/06 Operationを更新する
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

    /**
     * Display the specified resource.
     * [webapi]お詫びを表示
     * 2023/10/03
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    Log::info('login show START');

    Log::info('login show END');

    $comment = 'タイトル：【お詫び】メール送信時の社名誤送信について

    お客様各位
    
    いつも、Tax-Affairsをご使用頂きまして誠にありがとうございます。
    2023年9月29日にて、以下事案が発生致しました。
    お客様に於かれましては、大変ご迷惑をお掛けし申し訳ありませんでした。
    発生致しました、事案は修正を完了致しました。
    今後このような事が起きませんよう、チェック体制を強化して参ります。
    今後とも何卒宜しくお願い申し上げます。
    
    【内容】
    メール送信時、送信元名の誤送信。
    【詳細】
    システム開発時に開発環境連携に於いて、
    テスト環境の送信元名が反映されてしまった。';

    return view('components.apology', [
        'comment' => $comment,
    ]);
}

}
