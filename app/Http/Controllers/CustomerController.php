<?php
namespace App\Http\Controllers;

use File;
use Validator;
use DateTime;

use App\Models\Customer;
use App\Models\Spedelidate;
use App\Models\Yrendadjust;
use App\Models\Wokprocbook;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class CustomerController extends Controller
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
        Log::info('customer index START');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        if($organization_id == 0) {
            $customers = Customer::whereNull('deleted_at')
                            // `active_cancel` 1:契約 2:SPOT 3:解約',
                            ->orderBy('active_cancel', 'asc')
                            // 事業者コード
                            // ->orderBy('business_code', 'asc')
                            ->sortable()
                            ->paginate(300);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            // `active_cancel` 1:契約 2:SPOT 3:解約',
                            ->orderBy('active_cancel', 'asc')
                            // 事業者コード
                            // ->orderBy('business_code', 'asc')
                            ->sortable()
                            ->paginate(300);
        }

        $common_no ='00_2';
        $keyword   = null;
        $keyword2  = null;

        // 2022/08/05
        $frdate  = null;
        $todate  = null;

        $compacts = compact( 'common_no','customers', 'organization', 'organization_id','keyword','keyword2','frdate','todate' );

        //3Table Insert
        // $ret = $this->three_tableinsert();

        Log::info('customer index END');
        return view( 'customer.index', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('customer create START');

        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;
        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        $organizations = DB::table('organizations')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            // 組織の絞り込み
                            ->when($organization_id != 0, function ($query) use ($organization_id) {
                                return $query->where( 'id', $organization_id );
                            })
                            ->get();

        // customersを取得
        $customers = DB::table('customers')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

        // wokprocbooksを取得
        $wokprocbooks = DB::table('wokprocbooks')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->where('year', '=', $nowyear)
                            ->get();

        $common_no ='00_2';
        $compacts = compact( 'common_no','organizations', 'organization','organization_id','wokprocbooks','customers','nowyear' );

        Log::info('customer create END');
        return view( 'customer.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('customer store START');

        $organization = $this->auth_user_organization();

        $request->merge( ['organization_id'=> $organization->id] );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('customer/create')->withErrors($validator)->withInput();
        }

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());
// Log::debug('customer store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - customer store start');
        try {
            Customer::create($request->all());
            $customer = Customer::orderBy('id', 'desc')->first();
            $str                        = sprintf("%04d", $customer->id);
            $foldername                 = 'folder'. $str;
            $customer->foldername       = $foldername;

            //2022/11/22
            $customer->year             = $nowyear;
            // 2022/05/20
            //active_cancel アクティブ/解約 1:契約 2:SPOT 3:解約
            if($customer->active_cancel === 3) {
                //notificationl_flg 通知しない(1):通知する(2)
                $customer->notificationl_flg = 1;
            }
            $customer->save();         //  Inserts

            //active_cancel アクティブ/解約 1:契約 2:SPOT 3:解約
            if($customer->active_cancel === 1) {
                // 2022/05/20
                // $advisorsfee = new Advisorsfee();       // 顧問料金
                // $advisorsfee->organization_id = $customer->organization_id;
                // $advisorsfee->custm_id        = $customer->id;
                // $advisorsfee->year            = $nowyear;
                // $advisorsfee->advisor_fee     = $customer->advisor_fee; // 顧問料
                // $advisorsfee->save();                   //  Inserts

                //individual_class 法人(1):個人事業主(2)
                if($customer->individual_class === 1) {
                    $spedelidate = new Spedelidate();       // 納期の特例
                    $spedelidate->organization_id  = $customer->organization_id;
                    $spedelidate->custm_id         = $customer->id;
                    $spedelidate->year             = $nowyear;
                    $spedelidate->officecompe      = 0;       //'役員報酬
                    $spedelidate->employee         = 0;       //'従業員
                    $spedelidate->adept_flg        = 1;       //'達人フラグ
                    $spedelidate->payslip_flg      = 1;       //'納付書作成
                    $spedelidate->declaration_flg  = 1;       //'0円納付申告
                    $spedelidate->save();                     //  Inserts

                    $yrendadjust = new Yrendadjust();       // 年末調整
                    $yrendadjust->organization_id  = $customer->organization_id;
                    $yrendadjust->custm_id         = $customer->id;
                    $yrendadjust->year             = $nowyear;
                    $yrendadjust->absence_flg      = 1;   //'年調の有無 1:無 2:有
                    $yrendadjust->communica_flg    = 1;   //'伝達手段
                    $yrendadjust->salary_flg       = 1;   //'給与情報 1:未 2:済
                    $yrendadjust->refund_flg       = 1;   //'申請すれば還付あり 1:× 2:○
                    $yrendadjust->declaration_flg  = 1;   //'/0円納付申告 1:× 2:○
                    $yrendadjust->annual_flg       = 1;   //'年調申告 1:× 2:○
                    $yrendadjust->withhold_flg     = 1;   //'源泉徴収票 1:× 2:○
                    $yrendadjust->claim_flg        = 1;   //'請求フラグ 1:× 2:○
                    $yrendadjust->payment_flg      = 1;   //'入金確認フラグ 1:× 2:○
                    $yrendadjust->save();

                    // 2022/09/20 整理番号の初期設定
                    $wokprocbooks = DB::table('wokprocbooks')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
                    $count = $wokprocbooks->count();
                    $number = $nowyear . sprintf("%06d", ($count+1));

                    $wokprocbook = new Wokprocbook();       // 税理士業務処理簿
                    $wokprocbook->organization_id  = $customer->organization_id;
                    $wokprocbook->custm_id         = $customer->id;
                    $wokprocbook->year             = $nowyear;
                    // $str                           = $nowyear . sprintf("%06d", $customer->id);
                    $wokprocbook->refnumber        = $number;
                    // $wokprocbook->staff_no         = 7;     //矢不伸彦
                    $wokprocbook->staff_no         = auth::user()->id;     ////2020/09/20
                    $wokprocbook->save();
                }
            }

            DB::commit();

            $path = public_path().'/userdata'.'/'.$foldername;
            if(!File::exists($path)) {
                // path does not exist パスが存在しません
                File::makeDirectory($path, 0777, true, true);
            }

            Log::info('beginTransaction - customer store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - customer store end(rollback)');
        }

        Log::info('customer store END');
        // return redirect()->route('customer.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));

        // 重複クリック対策
        $request->session()->regenerateToken();
        return redirect()->route('customer.index');

    }

    public function three_tableinsert(){

        Log::info('customer three_tableinsert START');
        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());
        // Log::debug('customer store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - three_tableinsert start');
        $organization_id = 1;
        try {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','=', 1)
                            ->whereNull('deleted_at')
                            ->orderBy('id', 'asc')
                            ->get();

            foreach($customers as $customer) {

                $wokprocbook = new Wokprocbook();       // 税理士業務処理簿
                $wokprocbook->organization_id  = $customer->organization_id;
                $wokprocbook->custm_id         = $customer->id;
                $wokprocbook->year             = $nowyear;
                $str                           = $nowyear . sprintf("%06d", $customer->id);
                $wokprocbook->refnumber        = $str;
                $wokprocbook->staff_no         = 7;     //矢不伸彦
                // $wokprocbook->proc_date       = $request->proc_date;       // 処理年月日
                // $wokprocbook->contents_class  = $request->contents_class;  // 内容（税目等）
                // $wokprocbook->facts_class     = $request->facts_class;     // 顛末
                $wokprocbook->save();

                // $advisorsfee = new Advisorsfee();       // 顧問料金
                // $advisorsfee->organization_id = $customer->organization_id;
                // $advisorsfee->custm_id        = $customer->id;
                // $advisorsfee->year            = $nowyear;
                // $advisorsfee->advisor_fee     = $customer->advisor_fee; // 顧問料
                // $advisorsfee->save();                   //  Inserts

                //individual_class 法人(1):個人事業主(2)
                if($customer->individual_class === 1) {
                    $spedelidate = new Spedelidate();       // 納期の特例
                    $spedelidate->organization_id  = $customer->organization_id;
                    $spedelidate->custm_id         = $customer->id;
                    $spedelidate->year             = $nowyear;
                    $spedelidate->officecompe      = 0;       //'役員報酬
                    $spedelidate->employee         = 0;       //'従業員
                    $spedelidate->adept_flg        = 1;       //'達人フラグ
                    $spedelidate->payslip_flg      = 1;       //'納付書作成
                    $spedelidate->declaration_flg  = 1;       //'0円納付申告
                    $spedelidate->save();                     //  Inserts

                    $yrendadjust = new Yrendadjust();       // 年末調整
                    $yrendadjust->organization_id  = $customer->organization_id;
                    $yrendadjust->custm_id         = $customer->id;
                    $yrendadjust->year             = $nowyear;
                    $yrendadjust->absence_flg      = 1;   //'年調の有無 1:無 2:有
                    $yrendadjust->communica_flg    = 1;   //'伝達手段
                    $yrendadjust->salary_flg       = 1;   //'給与情報 1:未 2:済
                    $yrendadjust->refund_flg       = 1;   //'申請すれば還付あり 1:× 2:○
                    $yrendadjust->declaration_flg  = 1;   //'/0円納付申告 1:× 2:○
                    $yrendadjust->annual_flg       = 1;   //'年調申告 1:× 2:○
                    $yrendadjust->withhold_flg     = 1;   //'源泉徴収票 1:× 2:○
                    $yrendadjust->claim_flg        = 1;   //'請求フラグ 1:× 2:○
                    $yrendadjust->payment_flg      = 1;   //'入金確認フラグ 1:× 2:○
                    $yrendadjust->save();
                }
            }

            DB::commit();
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - three_tableinsert end(rollback)');
        }
        Log::info('customer three_tableinsert END');

        return;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('customer show START');
        Log::info('customer show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('customer edit START');

        $organization    = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        $organizations = DB::table('organizations')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            // 組織の絞り込み
                            ->when($organization_id != 0, function ($query) use ($organization_id) {
                                return $query->where( 'id', $organization_id );
                            })
                            ->get();

        $customer = customer::find($id);

        $wokprocbooks = Wokprocbook::select(
            'wokprocbooks.id as id'
            ,'wokprocbooks.organization_id as organization_id'
            ,'wokprocbooks.custm_id as custm_id'
            ,'wokprocbooks.year     as year'
            ,'wokprocbooks.refnumber as refnumber'
            ,'wokprocbooks.busi_class as busi_class'
            ,'wokprocbooks.contents_class as contents_class'
            ,'wokprocbooks.facts_class as facts_class'
            ,'wokprocbooks.proc_date as proc_date'
            ,'wokprocbooks.attach_doc as attach_doc'
            ,'wokprocbooks.filing_date as filing_date'
            ,'wokprocbooks.staff_no as staff_no'
            ,'wokprocbooks.remarks as remarks'

            ,'customers.id as customers_id'
            ,'customers.business_name as business_name'
            ,'customers.business_address as business_address'
            )
            ->leftJoin('customers', function ($join) {
                $join->on('wokprocbooks.custm_id', '=', 'customers.id');
            })
            ->where( 'wokprocbooks.custm_id', $customer->id )
            ->where( 'wokprocbooks.year', $nowyear )
            ->whereNull('customers.deleted_at')
            ->whereNull('wokprocbooks.deleted_at')
            ->whereNotNull ('wokprocbooks.proc_date')           // 2022/09/20
            // ->orderBy('wokprocbooks.proc_date', 'desc') // 2022/08/26  proc_date
            ->orderBy('wokprocbooks.proc_date', 'asc') // 2022/09/20  proc_date
            ->paginate(2);

        $compacts = compact( 'wokprocbooks','customer', 'organizations', 'organization', 'organization_id' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('customer edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('customer.edit', $compacts );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::info('customer update START');

        $validator = $this->get_validator($request,$id);
        if ($validator->fails()) {
            return redirect('customer/'.$id.'/edit')->withErrors($validator)->withInput();
        }
        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        $customer = Customer::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - customer update start');
        try {
                // 2022/11/07 field に NULLが入っているので 空文字''設定
                if (isset($request->represent_name)) {
                    // issetでtrueだった場合の処理
                    $represent_name = $request->represent_name;
                } else {
                    $represent_name = '';
                }
                if (isset($request->industry)) {
                    // issetでtrueだった場合の処理
                    $industry = $request->industry;
                } else {
                    $industry = '';
                }
                if (isset($request->business_address)) {
                    // issetでtrueだった場合の処理
                    $business_address = $request->business_address;
                } else {
                    $business_address = '';
                }
                if (isset($request->represent_address)) {
                    // issetでtrueだった場合の処理
                    $represent_address = $request->represent_address;
                } else {
                    $represent_address = '';
                }
                if (isset($request->tax_office)) {
                    // issetでtrueだった場合の処理
                    $tax_office = $request->tax_office;
                } else {
                    $tax_office = '';
                }
                if (isset($request->transfer_notification)) {
                    // issetでtrueだった場合の処理
                    $tax_office = $request->transfer_notification;
                } else {
                    $transfer_notification = 1;
                }
                if (isset($request->referral_destination)) {
                    // issetでtrueだった場合の処理
                    $tax_office = $request->referral_destination;
                } else {
                    $referral_destination = '';
                }
                $customer->business_code                 = $request->business_code;
                $customer->business_name                 = $request->business_name;
                $customer->closing_month                 = $request->closing_month;
                $customer->individual_class              = $request->individual_class;
                $customer->represent_name                = $represent_name;                 // 2022/11/07
                $customer->industry                      = $industry;                       // 2022/11/07
                $customer->prev_sales                    = $request->prev_sales;
                // $customer->represent_name                = $request->represent_name; // 2022/10/22 double
                $customer->prev_profit                   = $request->prev_profit;
                $customer->business_zipcode              = $request->business_zipcode;
                $customer->business_address              = $business_address;                // 2022/11/07
                $customer->business_tell                 = $request->business_tell;
                $customer->represent_zipcode             = $request->represent_zipcode;
                $customer->represent_address             = $represent_address;               // 2022/11/07
                $customer->represent_tell                = $request->represent_tell;
                $customer->tax_office                    = $tax_office;                      // 2022/11/07
                $customer->start_notification            = $request->start_notification;
                $customer->transfer_notification         = $transfer_notification;           // 2022/11/07
                $customer->blue_declaration              = $request->blue_declaration;
                $customer->special_delivery_date         = $request->special_delivery_date;
                $customer->interim_payment               = $request->interim_payment;
                $customer->consumption_tax               = $request->consumption_tax;
                $customer->consumption_tax_filing_period = $request->consumption_tax_filing_period;
                $customer->advisor_fee                   = $request->advisor_fee;
                $customer->active_cancel                 = $request->active_cancel;
                $customer->notificationl_flg             = $request->notificationl_flg;
                $customer->referral_destination          = $referral_destination;           // 2022/11/07
                $customer->final_accounting_at           = $request->final_accounting_at;
                $customer->memo_1                        = $request->memo_1;
                //  bill_flg              : 会計フラグ
                //  adept_flg             : 達人フラグ
                //  confirmation_flg      : 税理士確認フラグ
                //  report_flg            : 申告フラグ
                //  corporate_number      : 法人番号 2022/10/16 今後追加
                // $customer->corporate_number              = $request->corporate_number;
                $customer->email                         = $request->email;  // 2022/10/22
                $customer->bill_flg                      = $request->bill_flg;
                $customer->adept_flg                     = $request->adept_flg;
                $customer->confirmation_flg              = $request->confirmation_flg;
                $customer->report_flg                    = $request->report_flg;
                $customer->updated_at                    = now();

                // 2022/05/20
                //active_cancel アクティブ/解約 1:契約 2:SPOT 3:解約
                if($customer->active_cancel == 3) {
                    // Log::info('beginTransaction - customer update active_cancel =3');
                    //notificationl_flg 通知しない(1):通知する(2)
                    $customer->notificationl_flg = 1;
                }

                $customer->save();

                // 顧問料金
                // $advisorsfee = Advisorsfee::where('custm_id','=',$customer->id)
                //             ->first();
                // // $advisorsfee = Advisorsfee::find($customer->custm_id);
                // $advisorsfee->advisor_fee     = $request->advisor_fee; // 顧問料
                // $advisorsfee->updated_at      = now();
                // $result     = $advisorsfee->save();                   //  Update

                // 2022/08/26
                // 顧客管理、入力時に
                // 業務処理簿に同じ処理日があれば、上書きする
                // 業務処理簿に処理日がなければ、追加する
                // 税理士業務処理簿
                // 2022/09/20
                //-------------------------------------------------------------
                //- Request パラメータ
                //-------------------------------------------------------------
                $proc_date = $request->Input('proc_date');
        // Log::debug('customer update $proc_date = ' . $proc_date);
                $str = ( new DateTime($proc_date))->format('Y-m-d');
                if(is_null($proc_date) == true ) {    //（NULL型）の場合：TRUE
                    // $wokprocbooks = Wokprocbook::where('custm_id','=',$customer->id)
                    //             ->where( 'year', $nowyear )
                    //             ->first();
                    // $wokprocbooks->proc_date       = $request->proc_date;       // 処理年月日
                    // $wokprocbooks->contents_class  = $request->contents_class;  // 内容（税目等）
                    // $wokprocbooks->facts_class     = $request->facts_class;     // 顛末
                    // $wokprocbooks->updated_at      = now();
                    // $wokprocbooks->save();                   //  Update
                } else {
                    $wokprocbooks = Wokprocbook::where('custm_id','=',$customer->id)
                                ->where( 'year', $nowyear )
                                ->whereDate( 'proc_date', $str )
                                ->get();
                    $count       = $wokprocbooks->count();
        // Log::debug('customer update $request->proc_date = ' . $request->proc_date);
        // Log::debug('customer update $str = ' . $str);
        // Log::debug('customer update $count = ' . $count);
                    if($count >= 1) {
                        $wokprocbooks = Wokprocbook::where('custm_id','=',$customer->id)
                            ->where( 'year', $nowyear )
                            ->whereDate( 'proc_date', $str )
                            ->first();
                        $wokprocbooks->proc_date        = $str;       // 処理年月日
                        $wokprocbooks->contents_class   = $request->contents_class;  // 内容（税目等）
                        $wokprocbooks->facts_class      = $request->facts_class;     // 顛末
                        $wokprocbooks->updated_at       = now();
                        $wokprocbooks->save();          //  Update
                    } else {
                        // 2022/09/20 整理番号の初期設定
                        $wokprocbooks = DB::table('wokprocbooks')->get();
                        $count  = $wokprocbooks->count();
                        $number = $nowyear . sprintf("%06d", ($count+1));

                        $wokprocbooks = new Wokprocbook();
                        $wokprocbooks->organization_id  = $customer->organization_id;
                        $wokprocbooks->custm_id         = $customer->id;
                        $wokprocbooks->year             = $nowyear;
                        // $str                            = $nowyear . sprintf("%06d", $customer->id);
                        $wokprocbooks->refnumber        = $number;
                        // $wokprocbooks->staff_no         = 7;     //矢不伸彦
                        $wokprocbooks->staff_no         = auth::user()->id;          // 2022/09/20
                        $wokprocbooks->proc_date        = $str;       // 処理年月日
                        $wokprocbooks->contents_class   = $request->contents_class;  // 内容（税目等）
                        $wokprocbooks->facts_class      = $request->facts_class;     // 顛末
                        $wokprocbooks->save();          //  Add
                    }
                }

                // Log::debug('customer update customer = ' . $customer);

                DB::commit();
                Log::info('beginTransaction - customer update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - customer update end(rollback)');
        }

        Log::info('customer update END');
        // return redirect()->route('user.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));

        // 重複クリック対策
        $request->session()->regenerateToken();
        return redirect()->route('customer.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('customer destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - customer destroy start');
        //return redirect(route('customer.index'))->with('msg_danger', '許可されていない操作です');

        try {
            // customer::where('id', $id)->delete();
            $customer = Customer::find($id);
            $customer->deleted_at     = now();
            $result = $customer->save();
            DB::commit();
            Log::info('beginTransaction - customer destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - customer destroy end(rollback)');
        }

        Log::info('customer destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('customer.index');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Customer $customer, Request $request)
    {
        Log::info('customer serch START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword  = $request->Input('keyword');     //顧客名
        $keyword2 = $request->Input('keyword2');    //代表者名

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 日付が入力された
        if($keyword || $keyword2) {
            if($organization_id == 0) {
                $customers = Customer::where('organization_id','>=',$organization_id)
                // ($keyword)の絞り込み
                ->where('business_name', 'like', "%$keyword%")
                ->where('represent_name', 'like', "%$keyword2%")    //2022/09/29
                // 削除されていない
                ->whereNull('deleted_at')
                // `active_cancel` 1:契約 2:SPOT 3:解約',
                ->orderBy('active_cancel', 'asc')
                // 事業者コード
                // ->orderBy('business_code', 'asc')
                // sortable()を追加
                ->sortable()
                ->paginate(300);
            } else {
                $customers = Customer::where('organization_id','=',$organization_id)
                // ($keyword)の絞り込み
                ->where('business_name', 'like', "%$keyword%")
                ->where('represent_name', 'like', "%$keyword2%")    //2022/09/29
                // 削除されていない
                ->whereNull('deleted_at')
                // `active_cancel` 1:契約 2:SPOT 3:解約',
                ->orderBy('active_cancel', 'asc')
                // 事業者コード
                // ->orderBy('business_code', 'asc')
                // sortable()を追加
                ->sortable()
                ->paginate(300);
            }
        } else {
            if($organization_id == 0) {
                $customers = Customer::where('organization_id','>=',$organization_id)
                // 削除されていない
                ->whereNull('deleted_at')
                // `active_cancel` 1:契約 2:SPOT 3:解約',
                ->orderBy('active_cancel', 'asc')
                // 事業者コード
                // ->orderBy('business_code', 'asc')
                // sortable()を追加
                ->sortable()
                ->paginate(300);
            } else {
                $customers = Customer::where('organization_id','=',$organization_id)
                // 削除されていない
                ->whereNull('deleted_at')
                // `active_cancel` 1:契約 2:SPOT 3:解約',
                ->orderBy('active_cancel', 'asc')
                // 事業者コード
                // ->orderBy('business_code', 'asc')
                // sortable()を追加
                ->sortable()
                ->paginate(300);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no ='00_2';

        $keyword   = $keyword;
        $keyword2  = $keyword2;
        // 2022/08/26
        $frdate  = null;
        $todate  = null;

        $compacts = compact( 'common_no','customers','keyword','keyword2','frdate','todate' );
        Log::info('customer serch END');

        // return view('customer.index', ['customers' => $customers]);
        return view('customer.index', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
            'business_code'     => [
                                    'digits_between:10,10',
                                    'required',
                                    Rule::unique('customers','business_code')->ignore($id)->whereNull('deleted_at'),
                                ],
            'business_name'     => 'required',
            'individual_class'  => [
                                    'min:1',        //法人・個人 指定された値以上か
                                    'integer',
                                    'required',
                                ],
            'closing_month'     => [
                                    'min:1',        //決算月 指定された値以上か
                                    'integer',
                                    // 'required',
                                ],
            // 'represent_name'    => 'required',   // 2022/10/22
            // 'email'             => 'email',      // 2022/10/24
        ];

        $messages = [
            'business_code.digits_between'      => '事業者コードは10桁です。',
            'business_code.required'            => '事業者コードは入力必須項目です。',
            'business_code.unique'              => 'その事業者コードは既に登録されています。',
            'business_name.required'            => '事業者名は入力必須項目です。',
            'individual_class.min'              => '法人／個人を選択してください。',
            'individual_class.required'         => '法人／個人は入力必須項目です。',
            'closing_month.min'                 => '決算月を選択してください。',
            'closing_month.required'            => '決算月は入力必須項目です。',
            // 'represent_name.required'           => '代表者名は入力必須項目です。',    // 2022/10/22
            // 'email.required'                    => 'E-Mailは入力必須項目です。',     // 2022/10/24
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
    /**
     *
     */
}
