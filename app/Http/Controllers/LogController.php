<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function log(Request $request)
    //public function log()
    {
        Log::emergency('ログ', ['memo' => 'sample1']);
        Log::alert('ログ', ['memo' => 'sample1']);
        Log::critical('ログ', ['memo' => 'sample1']);
        Log::error('ログ', ['memo' => 'sample1']);
        Log::warning('ログ', ['memo' => 'sample1']);
        Log::notice('ログ', ['memo' => 'sample1']);
        Log::info('ログ', ['memo' => 'sample1']);
        Log::debug('ログ', ['memo' => 'sample1']);

        return view('log.log');
    }

}
