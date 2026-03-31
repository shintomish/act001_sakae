<?php

namespace App\Http\Controllers;

// use Validator;
// use App\Models\User;
use App\Models\Customer;
use App\Models\Applestabl;

use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Hash;

// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Foundation\Bus\DispatchesJobs;
// use Illuminate\Foundation\Validation\ValidatesRequests;
// use Illuminate\Routing\Controller as BaseController;

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

        //------ 2025/11/20 コメント
        //2023/01/11 organization_id == 0の判定削除
        // 今月の申告
        // if($submonth2 == 12) {
        //     $customers2 = Customer::where('organization_id','>=',$organization_id)
        //                 // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        //                 ->where('active_cancel','!=', 3)
        //                 //2023/01/11 Add
        //                 //individual_class 法人(1):個人事業主(2)
        //                 ->where('individual_class','=', 1)
        //                 ->where('closing_month','>=', $submonth2 )
        //                 ->whereNull('deleted_at');  // 2024/02/16 ADD
        //     $count2     = $customers2->count();

        //     $customers2 = Customer::where('organization_id','>=',$organization_id)
        //                 // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        //                 ->where('active_cancel','!=', 3)
        //                 //2023/01/11 Add
        //                 //individual_class 法人(1):個人事業主(2)
        //                 ->where('individual_class','=', 1)
        //                 ->where('closing_month','>=', $submonth2 )
        //                 ->whereNull('deleted_at')
        //                 ->sortable()
        //                 ->paginate(200, ['*'], 'customers2');

        // } else {
        //     $customers2 = Customer::where('organization_id','>=',$organization_id)
        //                 // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        //                 ->where('active_cancel','!=', 3)
        //                 //2023/01/11 Add
        //                 //individual_class 法人(1):個人事業主(2)
        //                 ->where('individual_class','=', 1)
        //                 ->where('closing_month','=', $submonth2 )
        //                 ->whereNull('deleted_at');  // 2024/02/16 ADD
        //     $count2     = $customers2->count();

        //     $customers2 = Customer::where('organization_id','>=',$organization_id)
        //                 // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        //                 ->where('active_cancel','!=', 3)
        //                 //2023/01/11 Add
        //                 //individual_class 法人(1):個人事業主(2)
        //                 ->where('individual_class','=', 1)
        //                 ->where('closing_month','=', $submonth2 )
        //                 ->whereNull('deleted_at')
        //                 ->sortable()
        //                 ->paginate(200, ['*'], 'customers2');
        // }
        // // 来月の申告
        // if($submonth1 == 12) {
        //     $customers3 = Customer::where('organization_id','>=',$organization_id)
        //                 // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        //                 ->where('active_cancel','!=', 3)
        //                 //2023/01/11 Add
        //                 //individual_class 法人(1):個人事業主(2)
        //                 ->where('individual_class','=', 1)
        //                 ->where('closing_month','>=', $submonth1 )
        //                 ->whereNull('deleted_at');  // 2024/02/16 ADD
        //     $count3     = $customers3->count();

        //     $customers3 = Customer::where('organization_id','>=',$organization_id)
        //                 // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        //                 ->where('active_cancel','!=', 3)
        //                 //2023/01/11 Add
        //                 //individual_class 法人(1):個人事業主(2)
        //                 ->where('individual_class','=', 1)
        //                 ->where('closing_month','>=', $submonth1 )
        //                 ->whereNull('deleted_at')
        //                 ->sortable()
        //                 ->paginate(200, ['*'], 'customers3');
        // } else {
        //     $customers3 = Customer::where('organization_id','>=',$organization_id)
        //                 // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        //                 ->where('active_cancel','!=', 3)
        //                 //2023/01/11 Add
        //                 //individual_class 法人(1):個人事業主(2)
        //                 ->where('individual_class','=', 1)
        //                 ->where('closing_month','=', $submonth1 )
        //                 ->whereNull('deleted_at');  // 2024/02/16 ADD
        //     $count3     = $customers3->count();

        //     $customers3 = Customer::where('organization_id','>=',$organization_id)
        //                 // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        //                 ->where('active_cancel','!=', 3)
        //                 //2023/01/11 Add
        //                 //individual_class 法人(1):個人事業主(2)
        //                 ->where('individual_class','=', 1)
        //                 ->where('closing_month','=', $submonth1 )
        //                 ->whereNull('deleted_at')
        //                 ->sortable()
        //                 ->paginate(200, ['*'], 'customers3');
        // }
        //------ 2025/11/20 コメント
        //------ 2025/11/20 Add
        //今月の申告データ取得（now）
        $customers2 = $this->getThisMonthTaxRet($nowmonth, $organization_id)->get();
        $count2     = $customers2->count();
        
        $customers2 = $this->getThisMonthTaxRet($nowmonth, $organization_id)
                            ->sortable()
                            ->paginate(200, ['*'], 'customers2');

        //来月の申告データ取得（next）
        $customers3 = $this->getNextMonthTaxRet($nowmonth, $organization_id)->get();
        $count3     = $customers3->count();

        $customers3 = $this->getNextMonthTaxRet($nowmonth, $organization_id)
                            ->sortable()
                            ->paginate(200, ['*'], 'customers3');
        //------ 2025/11/20 Add End

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

        // 2024/01/20
        $books = DB::table('books')
            // 削除されていない
            ->whereNull('deleted_at')
            ->first();

        $topurl = $books->topurl;

        $compacts = compact( 'userid','customers2','customers3','count2','count3','common_no','nowyear', 'topurl' );

        Log::info('office top index END $user->name = ' . print_r($user->name ,true));
        return view( 'top.index', $compacts);
    }
    /**
     * 2025/11/20
     * 今月の申告データ取得（now）
     * consumption_tax_filing_period 消費税申告の期間 1:１年 2:３か月ごと 3:毎月 2と3を表示
     * consumption_tax_filing_period  = 1の場合は、closing_month[決算月] = 今月を基準として２か月前が決算月の会社も表示する
     * 2:３か月ごと：決算月＋５か月、決算月＋８か月、決算月＋１１か月　の時に表示
     * 3:毎月 2と3を表示
     * I:$nowmonth I:organization_id
     */
    public function getThisMonthTaxRet(int $nowmonth, $organization_id)
    {
        Log::info('getThisMonthTaxRet START');

        // 2ヶ月前の月
        $twoMonthsAgo = (($nowmonth + 9) % 12) + 1;

        $ret_val = Customer::where('organization_id','>=',$organization_id)
                ->where('active_cancel','!=', 3)
                ->where('individual_class', 1)
                ->whereNull('deleted_at')
                ->where(function ($q) use ($nowmonth, $twoMonthsAgo) {

                    // ★毎月（3）
                    $q->where('consumption_tax_filing_period', 3)

                    // ★年1回（1） → 2ヶ月前が決算月なら表示
                    ->orWhere(function($q1) use ($twoMonthsAgo) {
                        $q1->where('consumption_tax_filing_period', 1)
                        ->where('closing_month', $twoMonthsAgo);
                    })

                    // ★3ヶ月毎（2）
                    ->orWhere(function($q2) use ($nowmonth) {
                        $q2->where('consumption_tax_filing_period', 2)
                            ->whereRaw("
                                (
                                    ((closing_month + 5  - 1) % 12 + 1) = ?
                                    OR ((closing_month + 8  - 1) % 12 + 1) = ?
                                    OR ((closing_month + 11 - 1) % 12 + 1) = ?
                                )
                            ", [$nowmonth, $nowmonth, $nowmonth]);
                    });
                });

        Log::info('getThisMonthTaxRet END');
        return $ret_val;
    }

    /**
     * 2025/11/20
     * 来月の申告データ取得（next）
     * consumption_tax_filing_period 消費税申告の期間 1:１年 2:３か月ごと 3:毎月 2と3を表示
     * consumption_tax_filing_period  = 1の場合は、closing_month[決算月] = 今月を基準として１か月前が決算月の会社も表示する
     * 2:3か月ごと：決算月＋６か月、決算月＋９か月、決算月＋１２か月　の時に表示
     * 3:毎月 2と3を表示
     * I:$nowmonth I:organization_id
     */
    // public function getNextMonthTaxRet(int $nowmonth, $organization_id)
    // {
    //     $nextmonth = ($nowmonth % 12) + 1;

    //     Log::info('getNextMonthTaxRet START');

    //     $ret_val = Customer::where('organization_id','>=',$organization_id)
    //         ->where('active_cancel','!=', 3)
    //         ->where('individual_class', 1)
    //         ->whereNull('deleted_at')
    //         ->where(function ($q) use ($nextmonth, $nowmonth) {

    //             // ★ 毎月
    //             $q->where('consumption_tax_filing_period', 3)

    //             // ★ 3ヶ月毎：closing_month + (6, 9, 12)
    //             ->orWhere(function($q2) use ($nextmonth) {
    //                 $q2->where('consumption_tax_filing_period', 2)
    //                     ->where(function($q3) use ($nextmonth) {
    //                         $q3->whereRaw("
    //                             (
    //                                 ((closing_month + 6  - 1) % 12 + 1) = ?
    //                                 OR ((closing_month + 9  - 1) % 12 + 1) = ?
    //                                 OR ((closing_month + 12 - 1) % 12 + 1) = ?
    //                             )
    //                         ", [$nextmonth, $nextmonth, $nextmonth]);
    //                     });
    //             })

    //         // ★ 年1回：来月ではなく「今月の1ヶ月前のみ」で判定
    //         ->orWhere(function($q4) use ($nowmonth) {

    //             $prev = (($nowmonth + 10) % 12) + 1; // 1ヶ月前（計算方法そのまま）

    //             $q4->where('consumption_tax_filing_period', 1)
    //             ->where('closing_month', $prev); // ★ 1ヶ月前のみ
    //         });

    //         });

    //     Log::info('getNextMonthTaxRet END');
    //     return $ret_val;
    // }

    // 今のロジックは
    // ❌「決算月基準」
    // ✅ 本来は「決算申告月（決算月＋2）基準で3か月周期」
    // ✅ 正しい考え方
    // ① 決算申告月 = closing_month + 2
    // 8月決算 → 10月が決算申告月
    // base = closing_month + 2
    // ② 消費税3か月申告月
    // base, base+3, base+6, base+9
    // 8月決算の場合：
    // 計算			月
    // 8 + 2 = 10		10月
    // 10 + 3 = 13 → 1月	
    // 10 + 6 = 16 → 4月	
    // 10 + 9 = 19 → 7月	
    // → 客先回答と完全一致 ✅
    // ✅ 修正ポイントまとめ
    // 区分				旧			新
    // 基準月		決算月			✅ 決算申告月（決算月＋2）
    // 3か月申告	+3,+6,+9,+12	✅ +0,+3,+6,+9
    // 年1回		決算月+1		✅ 決算月+2
    public function getNextMonthTaxRet(int $nowmonth, $organization_id)
    {
        $nextmonth = ($nowmonth % 12) + 1;

        return Customer::where('organization_id','>=',$organization_id)
            ->where('active_cancel','!=', 3)
            ->where('individual_class', 1)
            ->whereNull('deleted_at')
            ->where(function ($q) use ($nextmonth) {

                // ✅ 毎月申告
                $q->where('consumption_tax_filing_period', 3)

                // ✅ 3か月ごと（決算申告月 = 決算月+2 を基準に +0,+3,+6,+9）
                ->orWhere(function($q2) use ($nextmonth) {
                    $q2->where('consumption_tax_filing_period', 2)
                    ->whereRaw("
                            (
                                ((closing_month + 2      - 1) % 12 + 1) = ?
                            OR ((closing_month + 2 + 3  - 1) % 12 + 1) = ?
                            OR ((closing_month + 2 + 6  - 1) % 12 + 1) = ?
                            OR ((closing_month + 2 + 9  - 1) % 12 + 1) = ?
                            )
                        ", [$nextmonth, $nextmonth, $nextmonth, $nextmonth]);
                })

                // ✅ 年1回（決算申告月 = 決算月 +2）
                ->orWhere(function($q3) use ($nextmonth) {
                    $q3->where('consumption_tax_filing_period', 1)
                    ->whereRaw("((closing_month + 2 - 1) % 12 + 1) = ?", [$nextmonth]);
                });
            });
    }

    /**
     * 2025/11/20
     * 汎用：〇ヶ月後の表示月チェック関数 未使用
     */
    private function isDisplayMonth($closing_month, $nowmonth, array $adds)
    {
        foreach ($adds as $add) {
            $m = (($closing_month + $add - 1) % 12) + 1; // 1〜12へ補正
            if ($m == $nowmonth) {
                return true;
            }
        }
        return false;
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
        //2025/11/15
        $tax_filing_period   = $request->input('tax_filing_period');

        // Log::debug('bill_flg          : ' . $bill_flg);
        // Log::debug('adept_flg         : ' . $adept_flg);
        // Log::debug('confirmation_flg  : ' . $confirmation_flg);
        // Log::debug('report_flg        : ' . $report_flg);
        // Log::debug('consumption_tax   : ' . $consumption_tax);
        // Log::debug('tax_filing_period   : ' . $tax_filing_period);

                    //  bill_flg              : 会計フラグ
                    //  adept_flg             : 達人フラグ
                    //  confirmation_flg      : 税理士確認フラグ
                    //  report_flg            : 申告フラグ
                    //  consumption_tax       : 消費税フラグ
                    //  consumption_tax_filing_period       : 消費税申告期間フラグ

        $counts = array();
        $update = [];
        if( $request->exists('bill_flg')           ) $update['bill_flg']          = $request->input('bill_flg');
        if( $request->exists('adept_flg')          ) $update['adept_flg']         = $request->input('adept_flg');
        if( $request->exists('confirmation_flg')   ) $update['confirmation_flg']  = $request->input('confirmation_flg');
        if( $request->exists('report_flg')         ) $update['report_flg']        = $request->input('report_flg');
        //2022/05/20
        if( $request->exists('consumption_tax')    ) $update['consumption_tax']   = $request->input('consumption_tax');
        //2025/11/15
        if( $request->exists('tax_filing_period')  ) $update['consumption_tax_filing_period']   = $request->input('tax_filing_period');

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

    /**
     * [webapi] 今月の申告：〇→ー 一括リセット
     * 対象フラグ: bill_flg / adept_flg / confirmation_flg / report_flg を 1(ー) にリセット
     */
    public function reset_api(Request $request)
    {
        Log::info('top reset_api START');

        $ids = $request->input('ids', []);
        // 整数配列に変換
        $ids = array_map('intval', (array)$ids);
        $ids = array_filter($ids, fn($v) => $v > 0);

        if (empty($ids)) {
            return response()->json([['status' => ['error_code' => 400, 'message' => 'No ids given']]]);
        }

        DB::beginTransaction();
        try {
            Customer::whereIn('id', $ids)->update([
                'bill_flg'         => 1,
                'adept_flg'        => 1,
                'confirmation_flg' => 1,
                'report_flg'       => 1,
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);
            DB::commit();
            $status = ['error_code' => 0, 'message' => 'Reset completed'];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('top reset_api exception : ' . $e->getMessage());
            $status = ['error_code' => 501, 'message' => $e->getMessage()];
        }

        Log::info('top reset_api END');
        return response()->json([compact('status')]);
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
