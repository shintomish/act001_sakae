<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CameraCaptureController extends Controller
{
    public function create() {

        Log::info('camera create START');

        //FileNameは「latestinformation.pdf」固定 2022/09/24
        $books = DB::table('books')->first();
        $str   = ( new DateTime($books->info_date))->format('Y-m-d');
        $latestinfodate = '最新情報'.'('.$str.')';

        // Customer(複数レコード)情報を取得する
        $customer_findrec = $this->auth_customer_findrec();
        $customer_id = $customer_findrec[0]['id'];

        $jsonfile = storage_path() . "/tmp/customer_info_status_". $customer_id. ".json";
        $compacts = compact('customer_findrec','customer_id','jsonfile','latestinfodate' );

        Log::info('camera create END');
        return view( 'camera.index', $compacts );
    }

    public function store(Request $request) {

        $request->validate([
            'image' => ['required', 'file', 'image']
        ]);

        $result = false;

        try {

            $request->file('image')->store('cropped_images'); // storage/app/cropped_images フォルダへ保存
            $result = true;

        } catch (\Exception $e) {

            // エラーの場合

        }

        return [
            'result' => $result
        ];

    }
}
