<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * APIのログイン処理
     */
    public function login(Request $request)
    {
        Log::info('START');

        DB::beginTransaction();
        Log::info('beginTransaction - start');
        try{
            if (!Auth::attempt(request(['email', 'password']))) {
                Log::info('END-Error');
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            $accessToken = Auth::user()->createToken('authToken')->plainTextToken;
            Log::info('END-Success');

            DB::commit();
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - end(rollback)');
        }

        return response()->json([
            'access_token' => $accessToken,
        ]);
    }

        /**
     * APIのログアウト処理
     */
    public function logout(Request $request)
    {
        Log::info('START');

        $result = array();
        DB::beginTransaction();
        Log::info('beginTransaction - start');
        try{
            $user = $request->user();
            Log::debug('user = ' . print_r($user,true));

            if( $user ){
                // 全てのトークンを削除
                $user->tokens()->delete();
                $result = array(  'error_code' => 0
                , 'message'    => 'You have successfully logged off.' );
            }
            else{
                $result = array(  'error_code' => 421
                , 'message'    => 'The user for the token is not logged in.' );
            }
            DB::commit();
        }
        catch( Exception $e ){
            $result = array(  'error_code' => 422
                            , 'message'    => 'An exception error occurred during logoff processing.' );
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - end(rollback)');
        }

        $json = response()->json([ compact('result') ]);
        Log::debug('json = ' . print_r($json,true));

        Log::info('END');
        return $json;
    }

    /**
     * [TEST用]ログインユーザー情報取得
     */
    public function test_user(Request $request)
    {
        Log::info('START');

        $user = $request->user();
        Log::debug('user = ' . print_r($user,true));

        $json = response()->json( $user );
        Log::debug( '$json = ' . print_r($json,true) );
        Log::info('END');
        return $json;
    }

}
