<?php

namespace App\Http\Controllers;

use App\Models\Operation;

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
                    // 組織の絞り込み
                    // ->where('users.organization_id','=',$organization_id)
                    // 削除されていない
                    ->whereNull('operations.deleted_at')
                    ->whereNull('customers.deleted_at')
                    // ($keyword)の絞り込み '%'.$keyword.'%'
                    // sortable()を追加
                    ->sortable('id','status_flg','login_verified_at','business_name')
                    ->orderBy('operations.status_flg', 'asc')
                    ->orderBy('operations.login_verified_at', 'desc')
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
                ->leftJoin('operations', function ($join) {
                    $join->on('customers.id', '=', 'operations.user_id');
                })
                    // 組織の絞り込み
                    ->where('operations.organization_id','=',$organization_id)
                    // 削除されていない
                    ->whereNull('operations.deleted_at')
                    ->whereNull('customers.deleted_at')
                    // ($keyword)の絞り込み '%'.$keyword.'%'
                    // sortable()を追加
                    ->sortable('id','status_flg','login_verified_at','business_name')
                    ->orderBy('operations.status_flg', 'asc')
                    ->orderBy('operations.login_verified_at', 'desc')
                    ->paginate(300);
        }

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
                // 組織の絞り込み
                // ->when($organization_id != 0, function ($query) use ($organization_id) {
                //     return $query->where( 'id', $organization_id );
                // })
                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                // ->where('active_cancel','!=', 3)
                // 削除されていない
                ->whereNull('deleted_at')
                // 2021/12/13
                ->orderBy('customers.business_name', 'asc')
                ->get();

        $common_no ='00_ope';
        $keyword   = null;
        $keyword2  = null;

        $compacts = compact( 'common_no','operations', 'organizations','organization_id','customers','keyword','keyword2' );

        Log::info('operation index END');
        return view( 'operation.index', $compacts );
    }
}