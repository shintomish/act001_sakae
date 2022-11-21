<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Actlog;
use App\Models\User;
use App\Facades\Csv;

class ActlogController extends Controller
{
    public function index()
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__);
        Log::info('actlog index START');

        // 今月の月を取得
        $nowmonth = intval($this->get_now_month());

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        $users = User::where('deleted_at')
                ->get();

        $actlog = Actlog::where('user_id', '>', '0')
                ->whereNotNull('route')
                ->orderby('created_at', 'desc')
                ->paginate(300);

        $common_no = '0x';
        $compacts = compact( 'common_no','actlog' ,'organization_id','nowmonth','users');

        Log::info('actlog index END');
        return view( 'actlog.index', $compacts );
        // return ['data' => $actlog];
    }


    public function download(Request $request)
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());

        // 取得項目設定
        $head = ['created_at', 'route', 'status', 'remote_addr', 'user_agent', 'user_id'];

        // 抽出
        $data = Actlog::select( $head )
            -> where('user_id', '>', '0')
            -> whereNotNull('route')
            -> orderby('created_at', 'desc')
            -> get()
            -> toArray();

        // 自動付与の名前をヘッダーに追加
        $head[] = 'name';

        // CSV DOWNLOAD
        return Csv::download($data, $head, 'test.csv');
    }
}