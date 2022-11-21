<?php

namespace App\Http\Controllers;

//Add after namespace declaration
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
/**
   * Create a new controller instance.
   *
   * @return void
   */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

  /**
   * Get Media Library page
   * @return View
   */
    public function mediaLibrary(Request $request){
        $user_obj = auth()->user();
        // $media_obj = $user_obj->media->all();
        // mediaを取得
        $media_obj = DB::table('media')->get();

        Log::info('mediaLibrary start');
        return view('medialibrary', ['user_obj' => $user_obj, 'media_obj' => $media_obj ]);
    }


}
