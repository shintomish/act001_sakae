<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlashController extends Controller
{
    //
    public function getIndex()
    {
        // return view('flash.practice');
    }

    public function postIndex(Request $req)
    {
        // メールアドレスがあるか？ないか？
        // if($req->has('email')){
        //     session()->flash('flashmessage','登録完了！');
        // }else {
        //     session()->flash('flashmessage','エラー！：メールアドレスを入力してください');
        // }
        // return redirect('flash/practice');
    }
}
