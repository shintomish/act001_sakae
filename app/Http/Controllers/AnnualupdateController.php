<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Businesname;
use App\Models\Progrecheck;
use App\Models\Schedule;
use App\Models\Spedelidate;
use App\Models\Yrendadjust;
use App\Models\Advisorsfee;
use App\Models\CustomSelect;
use App\Models\Wokprocbook;
use App\Models\Parameter;

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

class AnnualupdateController extends Controller
{
    /**
     * Create a new controller instance.
     * 年度更新
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        Log::info('annualupdate edit START');

        $organization    = $this->auth_user_organization();
        $organization_id = $organization->id;
        $organizations = DB::table('organizations')
                    // 削除されていない
                    ->whereNull('deleted_at')
                    // 組織の絞り込み
                    ->when($organization_id != 0, function ($query) use ($organization_id) {
                        return $query->where( 'id', $organization_id );
                    })
                    ->get();

        if($organization_id == 0) {
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                                ->whereNull('deleted_at')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                ->get();
        }

        $businesname = Businesname::whereNull('deleted_at')
                                ->get();

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());
        // * 今年の年のstatusを取得2
        $nowstatus  = $this->get_now_status2();

        $keyword2  = $nowyear;
        $compacts = compact( 'businesname', 'customers', 'organization_id', 'nowstatus','nowyear','keyword2' );

        // Log::debug('annualupdate edit  nowstatus = ' . $nowstatus);

        Log::info('annualupdate edit END');

        return view('annualupdate.edit', $compacts );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Log::info('annualupdate update START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyyear   = $request->Input('year');
        // * 選択された年を取得
        $keyword2  = intval($keyyear);
        // 次年度
        $next_year = intval($keyyear) + 1;

        // * 今年の年を取得2
        $nowyear   = intval($this->get_now_year2());

        $organization    = $this->auth_user_organization();
        $organization_id = $organization->id;
        $organizations = DB::table('organizations')
                    // 削除されていない
                    ->whereNull('deleted_at')
                    // 組織の絞り込み
                    ->when($organization_id != 0, function ($query) use ($organization_id) {
                        return $query->where( 'id', $organization_id );
                    })
                    ->get();

        if($organization_id == 0) {
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            // 2023/09/16 active_cancel 3=解約
                            ->where('active_cancel', '<>', 3)
                            ->get();

            // 納期特例
            $spedelidates = Spedelidate::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // 年末調整
            $yrendadjusts = Yrendadjust::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // 顧問料金
            // $advisorsfee = Advisorsfee::where('organization_id','>=',$organization_id)
            //                 ->whereNull('deleted_at')
            //                 ->where('year', '=', $keyyear)
            //                 ->get();

            // 業務名
            $businesname = Businesname::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // 進捗チェック
            $progrecheck = Progrecheck::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // customselectsチェック
            $customselect = CustomSelect::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // wokprocbooktsチェック 税理士業務処理簿
            $wokprocbook = Wokprocbook::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            // 2023/09/16 active_cancel 3=解約
                            ->where('active_cancel', '<>', 3)
                            ->get();
            // 納期特例
            $spedelidates = Spedelidate::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // 年末調整
            $yrendadjusts = Yrendadjust::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // 顧問料金
            // $advisorsfee = Advisorsfee::where('organization_id','=',$organization_id)
            //                 ->whereNull('deleted_at')
            //                 ->where('year', '=', $keyyear)
            //                 ->get();

            // 業務名
            $businesname = Businesname::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // 進捗チェック
            $progrecheck = Progrecheck::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // customselectsチェック
            $customselect = CustomSelect::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();

            // wokprocbooktsチェック 税理士業務処理簿
            $wokprocbook = Wokprocbook::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year', '=', $keyyear)
                            ->get();
        }

        DB::beginTransaction();
        Log::info('beginTransaction - annualupdate update start');

        // Log::debug('annualupdate $customers->count()    = ' . $customers->count());
        // Log::debug('annualupdate $spedelidates->count() = ' . $spedelidates->count());
        // Log::debug('annualupdate $yrendadjusts->count() = ' . $yrendadjusts->count());
        // Log::debug('annualupdate $advisorsfee->count() = ' . $advisorsfee->count());
        // Log::debug('annualupdate $businesname->count() = ' . $businesname->count());
        // Log::debug('annualupdate update  $spedelidates = ' . print_r($spedelidates, true));
        // Log::debug('annualupdate update  $yrendadjusts = ' . print_r($yrendadjusts, true));
        // Log::debug('annualupdate update  $advisorsfee  = ' . print_r($advisorsfee, true));
        // Log::debug('annualupdate update $businesnamers = ' . print_r($businesname, true));

        try {
            // 納期特例
            if($spedelidates->count()) {
                foreach($spedelidates as $spedelidates2) {
                    $new_data = new Spedelidate();
                    $new_data->organization_id  = $spedelidates2->organization_id;
                    $new_data->custm_id         = $spedelidates2->custm_id;
                    $new_data->year             = $next_year;
                    $new_data->officecompe      = $spedelidates2->officecompe;  // 役員報酬
                    $new_data->employee         = $spedelidates2->employee;     // 従業員
                    $new_data->paymenttype      = $spedelidates2->paymenttype;  // 納付種別
                    $new_data->adept_flg        = 1;
                    $new_data->payslip_flg      = 1;
                    $new_data->declaration_flg  = 1;
                    $new_data->paydate_att      = $spedelidates2->paydate_att;  // Add支払日注意 役員報酬と支払日
                    $new_data->checklist        = null; // 確認事項
                    $new_data->chaneg_flg       = 1; // 役員報酬変更なしあり
                    $new_data->after_change     = null; // 変更後
                    $new_data->change_time      = null;
                    $new_data->linkage_pay      = null;
                    $new_data->save();           //  Inserts
                }
            }

            // 年末調整
            if($yrendadjusts->count()) {
                foreach($yrendadjusts as $yrendadjusts2) {
                    $new_data = new Yrendadjust();
                    $new_data->organization_id  = $yrendadjusts2->organization_id;
                    $new_data->custm_id         = $yrendadjusts2->custm_id;
                    $new_data->year             = $next_year;
                    $new_data->absence_flg      = $yrendadjusts2->absence_flg;      // 年調の有無 1:無 2:有
                    $new_data->trustees_no      = $yrendadjusts2->trustees_no;      // 受託人数
                    $new_data->communica_flg    = $yrendadjusts2->communica_flg;    // 伝達手段
                    $new_data->announce_at      = null;
                    $new_data->docinfor_at      = null;
                    $new_data->doccolle_at      = null;
                    $new_data->rrequest_at      = null;
                    $new_data->matecret_at      = null;
                    $new_data->salary_flg       = 1;
                    $new_data->remark_1         = null;
                    $new_data->remark_2         = null;
                    $new_data->cooperat         = null;
                    $new_data->refund_flg       = 1;
                    $new_data->declaration_flg  = 1;
                    $new_data->annual_flg       = 1;
                    $new_data->withhold_flg     = 1;
                    $new_data->claim_flg        = 1;
                    $new_data->payment_flg      = 1;
                    $new_data->save();           //  Inserts
                }
            }

            // 顧問料金
            // if($advisorsfee->count()) {
            //     foreach($advisorsfee as $advisorsfee2) {
            //         $new_data = new Advisorsfee();
            //         $new_data->organization_id  = $advisorsfee2->organization_id;
            //         $new_data->custm_id         = $advisorsfee2->custm_id;
            //         $new_data->year             = $next_year;
            //         $new_data->advisor_fee      = $advisorsfee2->advisor_fee;
            //         $new_data->fee_01           = 0;
            //         $new_data->fee_02           = 0;
            //         $new_data->fee_03           = 0;
            //         $new_data->fee_04           = 0;
            //         $new_data->fee_05           = 0;
            //         $new_data->fee_06           = 0;
            //         $new_data->fee_07           = 0;
            //         $new_data->fee_08           = 0;
            //         $new_data->fee_09           = 0;
            //         $new_data->fee_10           = 0;
            //         $new_data->fee_11           = 0;
            //         $new_data->fee_12           = 0;
            //         $new_data->save();           //  Inserts
            //     }
            // }

            // 業務名
            if($businesname->count()) {
                foreach($businesname as $businesname2) {
                    $new_data = new Businesname();
                    $new_data->organization_id  = $businesname2->organization_id;
                    $new_data->custm_id         = $businesname2->custm_id;
                    $new_data->year             = $next_year;
                    $new_data->businm_01        = $businesname2->businm_01;
                    $new_data->businm_02        = $businesname2->businm_02;
                    $new_data->businm_03        = $businesname2->businm_03;
                    $new_data->businm_04        = $businesname2->businm_04;
                    $new_data->businm_05        = $businesname2->businm_05;
                    $new_data->businm_06        = $businesname2->businm_06;
                    $new_data->businm_07        = $businesname2->businm_07;
                    $new_data->businm_08        = $businesname2->businm_08;
                    $new_data->businm_09        = $businesname2->businm_09;
                    $new_data->businm_10        = $businesname2->businm_10;
                    $new_data->businm_11        = $businesname2->businm_11;
                    $new_data->businm_12        = $businesname2->businm_12;
                    $new_data->businm_13        = $businesname2->businm_13;
                    $new_data->businm_14        = $businesname2->businm_14;
                    $new_data->businm_15        = $businesname2->businm_15;
                    $new_data->businm_16        = $businesname2->businm_16;
                    $new_data->businm_17        = $businesname2->businm_17;
                    $new_data->businm_18        = $businesname2->businm_18;
                    $new_data->businm_19        = $businesname2->businm_19;
                    $new_data->businm_20        = $businesname2->businm_20;
                    $new_data->save();           //  Inserts
                }
            }

            // 進捗チェック
            if($progrecheck->count()) {
                foreach($progrecheck as $progrecheck2) {
                    $new_data = new Progrecheck();
                    $new_data->organization_id  = $progrecheck2->organization_id;
                    $new_data->custm_id         = $progrecheck2->custm_id;
                    $new_data->year             = $next_year;
                    $new_data->businm_no        = $progrecheck2->businm_no;
                    $new_data->check_01         = 1;
                    $new_data->check_02         = 1;
                    $new_data->check_03         = 1;
                    $new_data->check_04         = 1;
                    $new_data->check_05         = 1;
                    $new_data->check_06         = 1;
                    $new_data->check_07         = 1;
                    $new_data->check_08         = 1;
                    $new_data->check_09         = 1;
                    $new_data->check_10         = 1;
                    $new_data->check_11         = 1;
                    $new_data->check_12         = 1;
                    $new_data->save();           //  Inserts
                }
            }

            // customselectチェック
            if($customselect->count()) {
                foreach($customselect as $customselect2) {
                    $new_data = new CustomSelect();
                    $new_data->organization_id  = $customselect2->organization_id;
                    $new_data->custm_id         = $customselect2->custm_id;
                    $new_data->year             = $next_year;
                    $new_data->business_name    = $customselect2->business_name;
                    $new_data->save();           //  Inserts
                }
            }

            // wokprocbooktsチェック 税理士業務処理簿 2022/05/22
            if($wokprocbook->count()) {
                foreach($wokprocbook as $wokprocbook2) {
                    $new_data = new Wokprocbook();
                    $new_data->organization_id  = $wokprocbook2->organization_id;
                    $new_data->custm_id         = $wokprocbook2->custm_id;
                    $new_data->year             = $next_year;
                    $str                        = $nowyear . sprintf("%06d", $wokprocbook2->custm_id);
                    $new_data->refnumber        = $str;
                    // 2023/09/16 見直し
                    // $new_data->staff_no         = 7;
                    $new_data->staff_no         = $wokprocbook2->staff_no;
                    $new_data->save();           //  Inserts
                }
            }

            // 2023/09/16 customers更新をコメント
            // customersチェック 2022/05/22
            // $next_year = 2022;
            // if($customers->count()) {
            //     foreach($customers as $customers2) {
            //         $customers2->year                  = $next_year;
            //         $customers2->prev_sales            = 0;		//前期売上
            //         $customers2->prev_profit           = 0;		//前期利益
            //         $customers2->start_notification    = 1;		//開始届 1:未提出 2:提出済み
            //         $customers2->transfer_notification = 1;		//異動届 1:必要なし 2:提出済み (未使用)
            //         $customers2->special_delivery_date = 1;		//納期の特例 1:未提出 2:提出済み
            //         $customers2->bill_flg              = 1;		//会計フラグ 1:× 2:○
            //         $customers2->adept_flg             = 1;		//達人フラグ 1:× 2:○
            //         $customers2->confirmation_flg      = 1;		//税理士確認フラグ 1:× 2:○
            //         $customers2->report_flg            = 1;		//申告フラグ 1:× 2:○
            //         $customers2->progress_report1      = Null;	//進捗報告1
            //         $customers2->progress_report2      = Null;	//進捗報告2
            //         $customers2->progress_report3      = Null;	//進捗報告3
            //         $customers2->progress_report4      = Null;	//進捗報告4
            //         $customers2->progress_report5      = Null;	//進捗報告5
            //         $customers2->memo_1                = Null;	//memo_1
            //         $customers2->memo_2                = Null;	//memo_2
            //         $customers2->memo_3                = Null;	//memo_3
            //         $customers2->memo_4                = Null;	//memo_4
            //         $customers2->memo_5                = Null;	//memo_5
            //         $customers2->final_accounting_at   = Null;	//会計処理日
            //         $customers2->updated_at            = now();
            //         $customers2->save();           				//  Inserts
            //     }
            // }

            DB::commit();

            Log::info('beginTransaction - annualupdate update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - annualupdate update end(rollback)');
        }

        // status(true)をSetする
        $status = "true";
        $this->json_put_status($next_year,$status);
        // * 今年の年のstatusを取得2
        $nowstatus  = $this->get_now_status2();
        $keyword2  = $next_year;
        $compacts = compact( 'businesname', 'customers', 'organization_id', 'nowstatus','keyword2' );

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.year'));

        Log::info('annualupdate update END');

        return view('annualupdate.edit', $compacts );
    }
    /**
    * 今年の年のstatusを取得2
    * @return string
    */
    public function get_now_status2(): string
    {
        $jsonfile = storage_path() . "/app/userdata/year_info.json";
        $jsonUrl = $jsonfile; //JSONファイルの場所とファイル名を記述
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $obj = json_decode($json, true);
            $obj = $obj["res"]["info"];
            foreach($obj as $key => $val) {
                $year   = $val["year"];
                $status = $val["status"];
            }
            // Log::info('client postUpload  jsonUrl OK');
        } else {
            $year = $this->get_now_year();
            $status = 'false';
            // echo "データがありません";
            // Log::info('client postUpload  jsonUrl NG');
        }

        return $status;
    }

     /**
     * status(true)をSetする
     */
    public function json_put_status($year,$status)
    {
        Log::info('annualupdate json_put_status  START');

        $jsonfile = "";
        $arr = array(
            "res" => array(
                "info" => array(
                    [
                        "year"       => $year,
                        "status"     => $status
                    ]
                )
            )
        );

        $arr_status = json_encode($arr);
        $jsonfile = storage_path() . "/app/userdata/year_info.json";

        file_put_contents($jsonfile , $arr_status);
        Log::info('annualupdate json_put_status  END');
    }
    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [


        ];

        $messages = [
                // 'custm_id.required'            => '会社名は入力必須項目です。',
                // 'custm_id.unique'              => 'その会社名は既に登録されています。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
}
