<?php

namespace App\Http\Controllers;

use App\Models\Operation;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class OperationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('operation index START');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        $operations = Operation::select(
            'operations.id as id'
            ,'operations.user_id as user_id'
            ,'operations.name as name'
            ,'operations.status_flg as status_flg'
            ,'operations.login_verified_at as login_verified_at'
            ,'operations.logout_verified_at as logout_verified_at'
            ,'operations.organization_id as organization_id'
            ,'operations.login_flg as login_flg'
            ,'operations.admin_flg as admin_flg'
            ,'customers.id as customers_id'
            ,'customers.business_name as business_name'
            )
            ->leftJoin('customers', function ($join) {
                $join->on('customers.id', '=', 'operations.user_id');
            })
            // 組織の絞り込み
            // ->where('users.organization_id','=',$organization_id)
            ->where('operations.id', '>=', '10')
            // 削除されていない
            ->whereNull('operations.deleted_at')
            ->whereNull('customers.deleted_at')
            // sortable()を追加
            ->sortable('status_flg','login_verified_at','business_name')
            ->orderBy('operations.status_flg', 'asc')
            ->orderBy('operations.login_verified_at', 'desc')
            ->orderBy('customers.business_name', 'asc')
            ->paginate(300);

        // 一覧の組織IDを組織名にするため organizationsを取得
        $organizations = DB::table('organizations')
            // 組織の絞り込み
            ->when($organization_id != 0, function ($query) use ($organization_id) {
                return $query->where( 'id', $organization_id );
            })
            // 削除されていない
            ->whereNull('deleted_at')
            ->get();

        // customersを取得
        $customers = DB::table('customers')
            // 削除されていない
            ->whereNull('deleted_at')
            // 2021/12/13
            ->orderBy('customers.business_name', 'asc')
            ->get();

        $common_no ='00_ope';
        $keyword   = null;
        $keyword2  = null;
        $frdate    = null;
        $todate    = null;

        $compacts = compact( 'common_no','operations', 'organizations','organization_id','customers','keyword','keyword2','frdate','todate' );

        Log::info('operation index END');
        return view( 'operation.index', $compacts );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Request $request)
    {
        Log::info('operation serch START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');      //名前検索
        $keyword2 = $request->Input('keyword2');    //顧客名検索

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 名前　顧客名が入力された
        if($keyword || $keyword2) {
            $operations = Operation::select(
                 'operations.id as id'
                ,'operations.user_id as user_id'
                ,'operations.name as name'
                ,'operations.status_flg as status_flg'
                ,'operations.login_verified_at as login_verified_at'
                ,'operations.logout_verified_at as logout_verified_at'
                ,'operations.organization_id as organization_id'
                ,'operations.login_flg as login_flg'
                ,'operations.admin_flg as admin_flg'
                ,'customers.id as customers_id'
                ,'customers.business_name as business_name'
            )
            ->leftJoin('customers', function ($join) {
                $join->on('customers.id', '=', 'operations.user_id');
            })
                ->where('operations.id', '>=', '10')
                // 削除されていない
                ->whereNull('operations.deleted_at')
                ->whereNull('customers.deleted_at')
                // ($keyword)の絞り込み '%'.$keyword.'%'
                ->where('operations.name', 'like', "%$keyword%") // 2022/09/20
                ->where('customers.business_name', 'like', "%$keyword2%") // 2022/09/29
                ->sortable('status_flg','login_verified_at','business_name')
                ->orderBy('operations.status_flg', 'asc')
                ->orderBy('operations.login_verified_at', 'desc')
                ->orderBy('customers.business_name', 'asc')
                ->paginate(300);
        // 名前　顧客名が未入力(空欄)
        } else {
            if($organization_id == 0) {
                $operations = Operation::select(
                    'operations.id as id'
                   ,'operations.user_id as user_id'
                   ,'operations.name as name'
                   ,'operations.status_flg as status_flg'
                   ,'operations.login_verified_at as login_verified_at'
                   ,'operations.logout_verified_at as logout_verified_at'
                   ,'operations.organization_id as organization_id'
                   ,'operations.login_flg as login_flg'
                   ,'operations.admin_flg as admin_flg'
                   ,'customers.id as customers_id'
                   ,'customers.business_name as business_name'
               )
               ->leftJoin('customers', function ($join) {
                   $join->on('customers.id', '=', 'operations.user_id');
               })
                   ->where('operations.organization_id','>=',$organization_id)
                   ->where('operations.id', '>=', '10')
                   // 削除されていない
                   ->whereNull('operations.deleted_at')
                   ->whereNull('customers.deleted_at')
                   ->sortable('status_flg','login_verified_at','business_name')
                   ->orderBy('operations.status_flg', 'asc')
                   ->orderBy('operations.login_verified_at', 'desc')
                   ->orderBy('customers.business_name', 'asc')
                   ->paginate(300);
            } else {
                $operations = Operation::select(
                    'operations.id as id'
                   ,'operations.user_id as user_id'
                   ,'operations.name as name'
                   ,'operations.status_flg as status_flg'
                   ,'operations.login_verified_at as login_verified_at'
                   ,'operations.logout_verified_at as logout_verified_at'
                   ,'operations.organization_id as organization_id'
                   ,'operations.login_flg as login_flg'
                   ,'operations.admin_flg as admin_flg'
                   ,'customers.id as customers_id'
                   ,'customers.business_name as business_name'
               )
               ->leftJoin('customers', function ($join) {
                   $join->on('customers.id', '=', 'operations.user_id');
               })
                   ->where('operations.organization_id','=',$organization_id)
                   ->where('operations.id', '>=', '10')
                   // 削除されていない
                   ->whereNull('operations.deleted_at')
                   ->whereNull('customers.deleted_at')
                   ->sortable('status_flg','login_verified_at','business_name')
                   ->orderBy('operations.status_flg', 'asc')
                   ->orderBy('operations.login_verified_at', 'desc')
                   ->orderBy('customers.business_name', 'asc')
                   ->paginate(300);
            }
        };

        // 一覧の組織IDを組織名にするため organizationsを取得
        $organizations = DB::table('organizations')
                // 組織の絞り込み
                ->when($organization_id != 0, function ($query) use ($organization_id) {
                    return $query->where( 'id', $organization_id );
                })
                // 削除されていない
                ->whereNull('deleted_at')
                ->get();

        // customersを取得
        $customers = DB::table('customers')
                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                // ->where('active_cancel','!=', 3)
                // 削除されていない
                ->whereNull('deleted_at')
                ->get();

        //  $data =  $this->jsonResponse($customers);
        //  Log::debug('user index $customers = ' . print_r($customers, true));
        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        $common_no ='00_ope';
        $keyword   = $keyword;
        $keyword2  = $keyword2;
        $frdate    = null;
        $todate    = null;

        $compacts = compact( 'common_no','operations', 'organizations','organization_id','customers','keyword','keyword2','frdate','todate' );

        Log::info('operation serch END');

        return view('operation.index', $compacts);
    }
    /**
     * []operation ログインしていない事業主の指定期間検索
     */
    public function periodsearch(Request $request)
    {
        Log::info('operation periodsearch START');

        $frdate      = $request->input('frdate');
        $todate      = $request->input('todate');

        // 開始/終了が入力された
        if(isset($frdate) && isset($todate)) {
            $stadate    = Carbon::parse($frdate)->startOfDay();
            $enddate    = Carbon::parse($todate)->endOfDay();
        } else {
            // 開始が入力 終了が未入力
            if(isset($frdate) && is_null($todate)) {
                // Log::info('operation periodsearch １');
                $stadate   = Carbon::parse($frdate)->startOfDay();
                $enddate   = Carbon::parse('2050-12-31')->endOfDay();
            // 開始が未入力 終了が入力
            } elseif(is_null($frdate) && isset($todate)) {
                // Log::info('operation periodsearch ２');
                $stadate   = Carbon::parse('2020-01-01')->startOfDay();
                $enddate   = Carbon::parse($todate)->endOfDay();
            // 開始/終了が未入力
            } else {
                // Log::info('operation periodsearch ３');
                $stadate   = Carbon::parse('2050-12-31')->startOfDay();
                $enddate   = Carbon::parse('2020-01-01')->endOfDay();
            }
        }

// Log::debug('operation periodsearch $frdate = ' . print_r($frdate, true));
// Log::debug('operation periodsearch $todate = ' . print_r($todate, true));
// Log::debug('operation periodsearch $stadate = ' . print_r($stadate, true));
// Log::debug('operation periodsearch $enddate = ' . print_r($enddate, true));

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        $operations = Operation::select(
            'operations.id as id'
            ,'operations.user_id as user_id'
            ,'operations.name as name'
            ,'operations.status_flg as status_flg'
            ,'operations.login_verified_at as login_verified_at'
            ,'operations.logout_verified_at as logout_verified_at'
            ,'operations.organization_id as organization_id'
            ,'operations.login_flg as login_flg'
            ,'operations.admin_flg as admin_flg'
            ,'customers.id as customers_id'
            ,'customers.business_name as business_name'
            )
            ->leftJoin('customers', function ($join) {
                $join->on('customers.id', '=', 'operations.user_id');
            })
            // 組織の絞り込み
            // ->where('users.organization_id','=',$organization_id)
            ->where('operations.id', '>=', '10')
            // 削除されていない
            ->whereNull('operations.deleted_at')
            ->whereNull('customers.deleted_at')
            //(whereBetween)login_verified_atが20xx/xx/xx ~ 20xx/xx/xxのデータを取得
            // 例えば「23年8月以前にログインしていない事業主のみ表示」
            // の場合は、
            // 左側の年月日を空欄にして、
            // 右側の年月日に左の②のように2023/08/01を入力または選択します。
            // 空欄　～　2023/08/01 で検索します。
            
            // 逆に「23年8月以降にログインしていない事業主のみ表示」
            // の場合は、
            // 左側の年月日に左の②のように2023/08/01を入力または選択します。
            // 右側の年月日を空欄にします。
            // 2023/08/01　～　空欄　で検索します
            // ->orWhere('last_login_at', '<', '2023-08-01');  // ログイン日時が2023年8月1日より前の行
            // ->orWhere('last_login_at', '>=', '2023-08-01'); // ログイン日時が2023年8月1日以降の行

            ->whereNull('login_verified_at') // ログイン日時がNULLの行
            ->orWhere('login_verified_at', '<',  $stadate) // ログイン日時が2023年8月1日より前の行
            ->orWhere('login_verified_at', '>=', $enddate) // ログイン日時が2023年8月1日以降の行

            // sortable()を追加
            ->sortable('status_flg','login_verified_at','business_name')
            ->orderBy('operations.status_flg', 'asc')
            ->orderBy('operations.login_verified_at', 'desc')
            ->orderBy('customers.business_name', 'asc')
            ->paginate(300);

        // 一覧の組織IDを組織名にするため organizationsを取得
        $organizations = DB::table('organizations')
            // 組織の絞り込み
            ->when($organization_id != 0, function ($query) use ($organization_id) {
                return $query->where( 'id', $organization_id );
            })
            // 削除されていない
            ->whereNull('deleted_at')
            ->get();

        // customersを取得
        $customers = DB::table('customers')
            // 削除されていない
            ->whereNull('deleted_at')
            // 2021/12/13
            ->orderBy('customers.business_name', 'asc')
            ->get();

        $common_no ='00_ope';
        $keyword   = null;
        $keyword2  = null;
        // $frdate    = null;
        // $todate    = null;

        $compacts = compact( 'common_no','operations', 'organizations','organization_id','customers','keyword','keyword2','frdate','todate' );

        Log::info('operation periodsearch END');
        return view( 'operation.index', $compacts );

    }

}