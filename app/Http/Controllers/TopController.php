<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Applestabl;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class TopController extends Controller
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
    public function index(Request $request)
    {
        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        Log::info('office top index START $user->name = ' . print_r($user->name ,true));

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        $nowmonth = intval($this->get_now_month());    //今月の月を取得

        // Debug
        // $nowmonth = 1;

        // ---- 2022/05/20 --------
        // 当月から2ケ月後を取得
        // $nowmonth2 = intval($this->get_specify_month(2));
        // 当月から3ケ月後を取得
        // $nowmonth3 = intval($this->get_specify_month(3));
        // ------------------------
        // 2022/08/31
        // 例えば、今日は８月ですので
        // 　決算１か月前　９月決算の会社
        // 　今月の申告　　６月決算の会社
        // 　来月の申告　　７月決算の会社
        // 　今月の決算　　８月決算の会社
        // 今月を基準として２か月前が決算月の会社の表示
        // $submonth2 = intval($this->get_submonth(2));
        $submonth2 = intval($this->getbase_submonth($nowmonth, 2 ));
        // 今月を基準として１か月前が決算月の会社の表示
        // $submonth1 = intval($this->get_sub_month());
        // * 基準月($strmon)の〇($mon)月前を取得
        $submonth1 = intval($this->getbase_submonth($nowmonth, 1 ));

        // Log::debug('top index $nowmonth  = ' . print_r($nowmonth ,true));
        // Log::debug('top index $submonth2  = ' . print_r($submonth2 ,true));
        // Log::debug('top index $submonth1  = ' . print_r($submonth1 ,true));

        // if($nowmonth2 > 10 ){
        //     if($nowmonth2 = 11) {
        //         $nowmonth2 = 1;
        //         $nowmonth3 = 2;
        //     } else {
        //         $nowmonth2 = 2;
        //         $nowmonth3 = 3;
        //     }
        // } else {
        //     if($nowmonth2 == 10 ){
        //         $nowmonth2 = $nowmonth2 + 2;
        //         $nowmonth3 = 1;             //3ケ月
        //     } else {
        //         $nowmonth2 = $nowmonth2 + 2;
        //         $nowmonth3 = $nowmonth3 + 3;
        //     }
        // }

        //2023/01/11 organization_id == 0の判定削除
        // 今月の申告
        if($submonth2 == 12) {
            $customers2 = Customer::where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        //2023/01/11 Add
                        //individual_class 法人(1):個人事業主(2)
                        ->where('individual_class','=', 1)
                        ->where('closing_month','>=', $submonth2 );
            $count2     = $customers2->count();

            $customers2 = Customer::where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        //2023/01/11 Add
                        //individual_class 法人(1):個人事業主(2)
                        ->where('individual_class','=', 1)
                        ->where('closing_month','>=', $submonth2 )
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->paginate(200, ['*'], 'customers2');

        } else {
            $customers2 = Customer::where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        //2023/01/11 Add
                        //individual_class 法人(1):個人事業主(2)
                        ->where('individual_class','=', 1)
                        ->where('closing_month','=', $submonth2 );
            $count2     = $customers2->count();

            $customers2 = Customer::where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        //2023/01/11 Add
                        //individual_class 法人(1):個人事業主(2)
                        ->where('individual_class','=', 1)
                        ->where('closing_month','=', $submonth2 )
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->paginate(200, ['*'], 'customers2');
        }
        // 来月の申告
        if($submonth1 == 12) {
            $customers3 = Customer::where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        //2023/01/11 Add
                        //individual_class 法人(1):個人事業主(2)
                        ->where('individual_class','=', 1)
                        ->where('closing_month','>=', $submonth1 );
            $count3     = $customers3->count();

            $customers3 = Customer::where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        //2023/01/11 Add
                        //individual_class 法人(1):個人事業主(2)
                        ->where('individual_class','=', 1)
                        ->where('closing_month','>=', $submonth1 )
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->paginate(200, ['*'], 'customers3');
        } else {
            $customers3 = Customer::where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        //2023/01/11 Add
                        //individual_class 法人(1):個人事業主(2)
                        ->where('individual_class','=', 1)
                        ->where('closing_month','=', $submonth1 );
            $count3     = $customers3->count();

            $customers3 = Customer::where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        //2023/01/11 Add
                        //individual_class 法人(1):個人事業主(2)
                        ->where('individual_class','=', 1)
                        ->where('closing_month','=', $submonth1 )
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->paginate(200, ['*'], 'customers3');
        }
        //2023/01/11
        // 今月の申請・設立 使用していないのでコメント
        // 今年の年を取得
        // $nowyear     = intval($this->get_now_year());
        // $applestabls = Applestabl::where('organization_id','=',$organization_id)
        //                 ->whereNull('deleted_at')
        //                 ->where('year','=', $nowyear )
        //                 ->orderByRaw('created_at DESC')
        //                 ->sortable()
        //                 ->paginate(2, ['*'], 'applestabls');

        $common_no = '00_3';

        // * 今年の年を取得
        $nowyear = $this->get_now_year();

        //2023/01/11
        // $compacts = compact( 'userid','customers2','customers3','count2','count3','applestabls','common_no','nowyear' );
        $compacts = compact( 'userid','customers2','customers3','count2','count3','common_no','nowyear' );

        Log::info('office top index END $user->name = ' . print_r($user->name ,true));
        return view( 'top.index', $compacts);
    }

    /**
     * [webapi]Customerテーブルの更新
     */
    public function update_api(Request $request)
    {
        Log::info('office top update_api top START');

        // Log::debug('update_api request = ' .print_r($request->all(),true));
        $id = $request->input('id');

        Log::info('office top update_api id : ' . print_r($id,true));

        // $organization      = $this->auth_user_organization();
        $bill_flg            = $request->input('bill_flg');
        $adept_flg           = $request->input('adept_flg');
        $confirmation_flg    = $request->input('confirmation_flg');
        $report_flg          = $request->input('report_flg');
        //2022/05/20
        $consumption_tax     = $request->input('consumption_tax');

        // Log::debug('bill_flg          : ' . $bill_flg);
        // Log::debug('adept_flg         : ' . $adept_flg);
        // Log::debug('confirmation_flg  : ' . $confirmation_flg);
        // Log::debug('report_flg        : ' . $report_flg);
        // Log::debug('consumption_tax   : ' . $consumption_tax);

                    //  bill_flg              : 会計フラグ
                    //  adept_flg             : 達人フラグ
                    //  confirmation_flg      : 税理士確認フラグ
                    //  report_flg            : 申告フラグ
                    //  consumption_tax       : 消費税フラグ

        $counts = array();
        $update = [];
        if( $request->exists('bill_flg')           ) $update['bill_flg']          = $request->input('bill_flg');
        if( $request->exists('adept_flg')          ) $update['adept_flg']         = $request->input('adept_flg');
        if( $request->exists('confirmation_flg')   ) $update['confirmation_flg']  = $request->input('confirmation_flg');
        if( $request->exists('report_flg')         ) $update['report_flg']        = $request->input('report_flg');
        //2022/05/20
        if( $request->exists('consumption_tax')    ) $update['consumption_tax']   = $request->input('consumption_tax');

        $update['updated_at'] = date('Y-m-d H:i:s');
        // Log::debug('update_api update : ' . print_r($update,true));

        $status = array();
        DB::beginTransaction();
        Log::info('office top update_api top beginTransaction - start');
        try{
            // 更新処理
            Customer::where( 'id', $id )->update($update);

            $status = array( 'error_code' => 0, 'message'  => 'Your data has been changed!' );

            DB::commit();
            Log::info('office top update_api top beginTransaction - end');
        }
        catch(Exception $e){
            Log::error('office top update_api top exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('office top update_api top beginTransaction - end(rollback)');
            echo "エラー：" . $e->getMessage();
            $status = array( 'error_code' => 501, 'message'  => $e->getMessage() );
        }

        Log::info('office top update_api top END');
        return response()->json([ compact('status','counts') ]);
    }

    public function post(Request $data)
    {
        // Log::info('top post START');
        // Log::info('top post END');
        // // ホーム画面へリダイレクト
        // return redirect('/user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('top show START');
        Log::info('top show END');
    }

}
