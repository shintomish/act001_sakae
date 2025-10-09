<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
    // 2021/12/10
    // Route::group(['middleware' => ['api', 'cors']], function(){
    //     Route::options('articles', function() {
    //         return response()->json();
    //     });
    //     Route::resource('articles', 'Api\ArticlesController');
    // });
