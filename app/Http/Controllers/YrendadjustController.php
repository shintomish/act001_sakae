<?php
namespace App\Http\Controllers;

use File;
use Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Yrendadjust;
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

class YrendadjustController extends Controller
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
        Log::info('yrendadjust index START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
            $yrendadjusts = Yrendadjust::select(
                             'yrendadjusts.id               as id'
                            ,'yrendadjusts.organization_id  as organization_id'
                            ,'yrendadjusts.custm_id         as custm_id'
                            ,'yrendadjusts.year             as year'
                            ,'yrendadjusts.absence_flg      as absence_flg'
                            ,'yrendadjusts.trustees_no      as trustees_no'
                            ,'yrendadjusts.communica_flg    as communica_flg'
                            ,'yrendadjusts.announce_at      as announce_at'
                            ,'yrendadjusts.docinfor_at      as docinfor_at'
                            ,'yrendadjusts.doccolle_at      as doccolle_at'
                            ,'yrendadjusts.rrequest_at      as rrequest_at'
                            ,'yrendadjusts.matecret_at      as matecret_at'
                            ,'yrendadjusts.salary_flg       as salary_flg'
                            ,'yrendadjusts.remark_1         as remark_1'
                            ,'yrendadjusts.remark_2         as remark_2'
                            ,'yrendadjusts.cooperat         as cooperat'
                            ,'yrendadjusts.refund_flg       as refund_flg'
                            ,'yrendadjusts.declaration_flg  as declaration_flg'
                            ,'yrendadjusts.annual_flg       as annual_flg'
                            ,'yrendadjusts.withhold_flg     as withhold_flg'
                            ,'yrendadjusts.claim_flg        as claim_flg'
                            ,'yrendadjusts.payment_flg      as payment_flg'

                            ,'customers.id               as customers_id'
                            ,'customers.business_code    as business_code'
                            ,'customers.business_name    as business_name'
                            ,'customers.represent_name   as represent_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('yrendadjusts.custm_id', '=', 'customers.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('yrendadjusts.deleted_at')
                            ->where('yrendadjusts.year','=',$nowyear)
                            ->sortable()
                            ->orderBy('yrendadjusts.id', 'desc')
                            ->paginate(300);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

            $yrendadjusts = Yrendadjust::select(
                            'yrendadjusts.id                as id'
                            ,'yrendadjusts.organization_id  as organization_id'
                            ,'yrendadjusts.custm_id         as custm_id'
                            ,'yrendadjusts.year             as year'
                            ,'yrendadjusts.absence_flg      as absence_flg'
                            ,'yrendadjusts.trustees_no      as trustees_no'
                            ,'yrendadjusts.communica_flg    as communica_flg'
                            ,'yrendadjusts.announce_at      as announce_at'
                            ,'yrendadjusts.docinfor_at      as docinfor_at'
                            ,'yrendadjusts.doccolle_at      as doccolle_at'
                            ,'yrendadjusts.rrequest_at      as rrequest_at'
                            ,'yrendadjusts.matecret_at      as matecret_at'
                            ,'yrendadjusts.salary_flg       as salary_flg'
                            ,'yrendadjusts.remark_1         as remark_1'
                            ,'yrendadjusts.remark_2         as remark_2'
                            ,'yrendadjusts.cooperat         as cooperat'
                            ,'yrendadjusts.refund_flg       as refund_flg'
                            ,'yrendadjusts.declaration_flg  as declaration_flg'
                            ,'yrendadjusts.annual_flg       as annual_flg'
                            ,'yrendadjusts.withhold_flg     as withhold_flg'
                            ,'yrendadjusts.claim_flg        as claim_flg'
                            ,'yrendadjusts.payment_flg      as payment_flg'

                            ,'customers.id               as customers_id'
                            ,'customers.business_code    as business_code'
                            ,'customers.business_name    as business_name'
                            ,'customers.represent_name   as represent_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('yrendadjusts.custm_id', '=', 'customers.id');
                            })
                            ->where('yrendadjusts.organization_id','=',$organization_id)
                            ->whereNull('customers.deleted_at')
                            ->whereNull('yrendadjusts.deleted_at')
                            ->where('yrendadjusts.year','=',$nowyear)
                            ->sortable()
                            ->orderBy('yrendadjusts.id', 'desc')
                            ->paginate(300);
        }
        $common_no = '04';
        $keyword2  = null;

        $compacts = compact( 'userid','common_no','customers','yrendadjusts','nowyear','keyword2' );

        Log::info('yrendadjust index END');
        return view( 'yrendadjust.index', $compacts );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function input(Request $request)
    {
        Log::info('yrendadjust input START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
            $yrendadjusts = Yrendadjust::select(
                             'yrendadjusts.id               as id'
                            ,'yrendadjusts.organization_id  as organization_id'
                            ,'yrendadjusts.custm_id         as custm_id'
                            ,'yrendadjusts.year             as year'
                            ,'yrendadjusts.absence_flg      as absence_flg'
                            ,'yrendadjusts.trustees_no      as trustees_no'
                            ,'yrendadjusts.communica_flg    as communica_flg'
                            ,'yrendadjusts.announce_at      as announce_at'
                            ,'yrendadjusts.docinfor_at      as docinfor_at'
                            ,'yrendadjusts.doccolle_at      as doccolle_at'
                            ,'yrendadjusts.rrequest_at      as rrequest_at'
                            ,'yrendadjusts.matecret_at      as matecret_at'
                            ,'yrendadjusts.salary_flg       as salary_flg'
                            ,'yrendadjusts.remark_1         as remark_1'
                            ,'yrendadjusts.remark_2         as remark_2'
                            ,'yrendadjusts.cooperat         as cooperat'
                            ,'yrendadjusts.refund_flg       as refund_flg'
                            ,'yrendadjusts.declaration_flg  as declaration_flg'
                            ,'yrendadjusts.annual_flg       as annual_flg'
                            ,'yrendadjusts.withhold_flg     as withhold_flg'
                            ,'yrendadjusts.claim_flg        as claim_flg'
                            ,'yrendadjusts.payment_flg      as payment_flg'

                            ,'customers.id               as customers_id'
                            ,'customers.business_code    as business_code'
                            ,'customers.business_name    as business_name'
                            ,'customers.represent_name   as represent_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('yrendadjusts.custm_id', '=', 'customers.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('yrendadjusts.deleted_at')
                            ->where('yrendadjusts.year','=',$nowyear)
                            ->sortable()
                            // ->orderBy('yrendadjusts.id', 'desc')
                            ->paginate(300);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

            $yrendadjusts = Yrendadjust::select(
                            'yrendadjusts.id                as id'
                            ,'yrendadjusts.organization_id  as organization_id'
                            ,'yrendadjusts.custm_id         as custm_id'
                            ,'yrendadjusts.year             as year'
                            ,'yrendadjusts.absence_flg      as absence_flg'
                            ,'yrendadjusts.trustees_no      as trustees_no'
                            ,'yrendadjusts.communica_flg    as communica_flg'
                            ,'yrendadjusts.announce_at      as announce_at'
                            ,'yrendadjusts.docinfor_at      as docinfor_at'
                            ,'yrendadjusts.doccolle_at      as doccolle_at'
                            ,'yrendadjusts.rrequest_at      as rrequest_at'
                            ,'yrendadjusts.matecret_at      as matecret_at'
                            ,'yrendadjusts.salary_flg       as salary_flg'
                            ,'yrendadjusts.remark_1         as remark_1'
                            ,'yrendadjusts.remark_2         as remark_2'
                            ,'yrendadjusts.cooperat         as cooperat'
                            ,'yrendadjusts.refund_flg       as refund_flg'
                            ,'yrendadjusts.declaration_flg  as declaration_flg'
                            ,'yrendadjusts.annual_flg       as annual_flg'
                            ,'yrendadjusts.withhold_flg     as withhold_flg'
                            ,'yrendadjusts.claim_flg        as claim_flg'
                            ,'yrendadjusts.payment_flg      as payment_flg'

                            ,'customers.id               as customers_id'
                            ,'customers.business_code    as business_code'
                            ,'customers.business_name    as business_name'
                            ,'customers.represent_name   as represent_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('yrendadjusts.custm_id', '=', 'customers.id');
                            })
                            ->where('yrendadjusts.organization_id','=',$organization_id)
                            ->whereNull('customers.deleted_at')
                            ->whereNull('yrendadjusts.deleted_at')
                            ->where('yrendadjusts.year','=',$nowyear)
                            ->sortable()
                            // ->orderBy('yrendadjusts.id', 'desc')
                            ->paginate(300);
        }
        $common_no = '04';
        $keyword2  = null;

        $compacts = compact( 'userid','common_no','customers','yrendadjusts','nowyear','keyword2' );

        Log::info('yrendadjust input END');
        return view( 'yrendadjust.input', $compacts );
    }

    /**
     * [webapi]yrendadjustテーブルの更新
     */
    public function update_api(Request $request)
    {
        Log::info('update_api yrendadjust START');

        // Log::debug('update_api request = ' .print_r($request->all(),true));
        $id = $request->input('id');

        // $organization      = $this->auth_user_organization();
        $absence_flg     = $request->input('absence_flg');     //'年調の有無 1:無 2:有'
        $trustees_no     = $request->input('trustees_no');     //'受託人数'
        $communica_flg   = $request->input('communica_flg');   //'伝達手段'
        $announce_at     = $request->input('announce_at');     //アナウンス日
        $docinfor_at     = $request->input('docinfor_at');     //書類の案内日
        $doccolle_at     = $request->input('doccolle_at');     //資料回収日
        $rrequest_at     = $request->input('rrequest_at');     //資料再請求日
        $matecret_at     = $request->input('matecret_at');     //資料作成日
        $salary_flg      = $request->input('salary_flg');      //給与情報 1:未 2:済
        $remark_1        = $request->input('remark_1');        //備考1
        $remark_2        = $request->input('remark_2');        //備考2
        $cooperat        = $request->input('cooperat');        //納特納付書の連携
        $refund_flg      = $request->input('refund_flg');      //申請すれば還付あり 1:× 2:○
        $declaration_flg = $request->input('declaration_flg'); //0円納付申告 1:× 2:○
        $annual_flg      = $request->input('annual_flg');      //年調申告 1:× 2:○
        $withhold_flg    = $request->input('withhold_flg');    //源泉徴収票 1:× 2:○
        $claim_flg       = $request->input('claim_flg');       //請求フラグ 1:× 2:○
        $payment_flg     = $request->input('payment_flg');     //入金確認フラグ 1:× 2:○

        // Log::debug('organization_id   : ' . $organization->id);
        // Log::debug('proc_date         : ' . $proc_date);
        // Log::debug('attach_doc        : ' . $attach_doc);

        $counts = array();
        $update = [];
        if( $request->exists('absence_flg')     ) $update['absence_flg']      = $request->input('absence_flg');
        if( $request->exists('trustees_no')     ) $update['trustees_no']      = $request->input('trustees_no');
        if( $request->exists('communica_flg')   ) $update['communica_flg']    = $request->input('communica_flg');
        if( $request->exists('announce_at')     ) $update['announce_at']      = $request->input('announce_at');
        if( $request->exists('docinfor_at')     ) $update['docinfor_at']      = $request->input('docinfor_at');
        if( $request->exists('doccolle_at')     ) $update['doccolle_at']      = $request->input('doccolle_at');
        if( $request->exists('rrequest_at')     ) $update['rrequest_at']      = $request->input('rrequest_at');
        if( $request->exists('matecret_at')     ) $update['matecret_at']      = $request->input('matecret_at');
        if( $request->exists('salary_flg')      ) $update['salary_flg']       = $request->input('salary_flg');
        if( $request->exists('remark_1')        ) $update['remark_1']         = $request->input('remark_1');
        if( $request->exists('remark_2')        ) $update['remark_2']         = $request->input('remark_2');
        if( $request->exists('cooperat')        ) $update['cooperat']         = $request->input('cooperat');
        if( $request->exists('refund_flg')      ) $update['refund_flg']       = $request->input('refund_flg');
        if( $request->exists('declaration_flg') ) $update['declaration_flg']  = $request->input('declaration_flg');
        if( $request->exists('annual_flg')      ) $update['annual_flg']       = $request->input('annual_flg');
        if( $request->exists('withhold_flg')    ) $update['withhold_flg']     = $request->input('withhold_flg');
        if( $request->exists('claim_flg')       ) $update['claim_flg']        = $request->input('claim_flg');
        if( $request->exists('payment_flg')     ) $update['payment_flg']      = $request->input('payment_flg');

        $update['updated_at'] = date('Y-m-d H:i:s');
        // Log::debug('update_api update : ' . print_r($update,true));

        $status = array();
        DB::beginTransaction();
        Log::info('update_api yrendadjust beginTransaction - start');
        try{
            // 更新処理
            Yrendadjust::where( 'id', $id )->update($update);

            $status = array( 'error_code' => 0, 'message'  => 'Your data has been changed!' );

            DB::commit();
            Log::info('update_api yrendadjust beginTransaction - end');
        }
        catch(Exception $e){
            Log::error('update_api yrendadjust exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('update_api yrendadjust beginTransaction - end(rollback)');
            echo "エラー：" . $e->getMessage();
            $status = array( 'error_code' => 501, 'message'  => $e->getMessage() );
        }

        Log::info('update_api yrendadjust END');
        return response()->json([ compact('status','counts') ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('yrendadjust create START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;
        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','=', 1)
                                // `individual_class` int NOT NULL DEFAULT '1' COMMENT '法人(1):個人事業主(2)',
                                ->where('individual_class','=', 1)
                                ->whereNull('deleted_at')
                                ->orderby('id','desc')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','=', 1)
                                // `individual_class` int NOT NULL DEFAULT '1' COMMENT '法人(1):個人事業主(2)',
                                ->where('individual_class','=', 1)
                                ->whereNull('deleted_at')
                                ->orderby('id','desc')
                                ->get();
        }

        // yrendadjustsを取得
        $yrendadjusts = DB::table('yrendadjusts')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

        $compacts = compact( 'customers','yrendadjusts','organization_id','nowyear' );

        Log::info('yrendadjust create END');
        return view( 'yrendadjust.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('yrendadjust store START');

        $organization = $this->auth_user_organization();

        $request->merge( ['organization_id'=> $organization->id] );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('yrendadjust/create')->withErrors($validator)->withInput();
        }

// Log::debug('yrendadjusts store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - yrendadjust store start');
        try {
            Yrendadjust::create($request->all());
            DB::commit();

            Log::info('beginTransaction - yrendadjust store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - yrendadjust store end(rollback)');
        }

        Log::info('yrendadjust store END');
        // return redirect()->route('customer.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('yrendadjust.input');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('yrendadjust show START');
        Log::info('yrendadjust show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('yrendadjust edit START');

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
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                // 2022/10/17
                                // ->where('active_cancel','=', 1)
                                // `individual_class` int NOT NULL DEFAULT '1' COMMENT '法人(1):個人事業主(2)',
                                ->where('individual_class','=', 1)
                                ->whereNull('deleted_at')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                // 2022/10/17
                                // ->where('active_cancel','=', 1)
                                // `individual_class` int NOT NULL DEFAULT '1' COMMENT '法人(1):個人事業主(2)',
                                ->where('individual_class','=', 1)
                                ->whereNull('deleted_at')
                                ->get();
        }

        $yrendadjust = Yrendadjust::find($id);

        $compacts = compact( 'yrendadjust', 'customers', 'organization_id' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('yrendadjust edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('yrendadjust.edit', $compacts );
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
        Log::info('yrendadjust update START');

        $validator = $this->get_validator2($request,$id);
        if ($validator->fails()) {
            return redirect('yrendadjust/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $yrendadjust = Yrendadjust::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - yrendadjust update start');
        try {
                $yrendadjust->year             = $request->year;
                $yrendadjust->absence_flg      = $request->absence_flg;
                $yrendadjust->trustees_no      = $request->trustees_no;
                $yrendadjust->communica_flg    = $request->communica_flg;
                $yrendadjust->announce_at      = $request->announce_at;
                $yrendadjust->docinfor_at      = $request->docinfor_at;
                $yrendadjust->doccolle_at      = $request->doccolle_at;
                $yrendadjust->rrequest_at      = $request->rrequest_at;
                $yrendadjust->matecret_at      = $request->matecret_at;
                $yrendadjust->salary_flg       = $request->salary_flg;
                $yrendadjust->remark_1         = $request->remark_1;
                $yrendadjust->remark_2         = $request->remark_2;
                $yrendadjust->cooperat         = $request->cooperat;
                $yrendadjust->refund_flg       = $request->refund_flg;
                $yrendadjust->declaration_flg  = $request->declaration_flg;
                $yrendadjust->annual_flg       = $request->annual_flg;
                $yrendadjust->withhold_flg     = $request->withhold_flg;
                $yrendadjust->claim_flg        = $request->claim_flg;
                $yrendadjust->payment_flg      = $request->payment_flg;
                $yrendadjust->updated_at       = now();

                $result = $yrendadjust->save();

                // Log::debug('yrendadjust update = ' . $yrendadjust);

                DB::commit();
                Log::info('beginTransaction - yrendadjust update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - yrendadjust update end(rollback)');
        }

        Log::info('yrendadjust update END');
        // return redirect()->route('user.input')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('yrendadjust.input');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('yrendadjust destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - yrendadjust destroy start');
        //return redirect(route('customer.input'))->with('msg_danger', '許可されていない操作です');

        try {
            // customer::where('id', $id)->delete();
            $yrendadjust = yrendadjust::find($id);
            $yrendadjust->deleted_at     = now();
            $result = $yrendadjust->save();
            DB::commit();
            Log::info('beginTransaction - yrendadjust destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - yrendadjust destroy end(rollback)');
        }

        Log::info('yrendadjust destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('yrendadjust.input');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Yrendadjust $yrendadjust, Request $request)
    {
        Log::info('yrendadjust serch START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');
        $keyyear = $request->Input('year');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 日付or年が入力された
        if($keyword || $keyyear) {
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // yrendadjustsを取得
                $yrendadjusts = Yrendadjust::select(
                                    'yrendadjusts.id               as id'
                                    ,'yrendadjusts.organization_id as organization_id'
                                    ,'yrendadjusts.custm_id        as custm_id'
                                    ,'yrendadjusts.year            as year'
                                    ,'yrendadjusts.absence_flg     as absence_flg'
                                    ,'yrendadjusts.trustees_no     as trustees_no'
                                    ,'yrendadjusts.communica_flg   as communica_flg'
                                    ,'yrendadjusts.announce_at     as announce_at'
                                    ,'yrendadjusts.docinfor_at     as docinfor_at'
                                    ,'yrendadjusts.doccolle_at     as doccolle_at'
                                    ,'yrendadjusts.rrequest_at     as rrequest_at'
                                    ,'yrendadjusts.matecret_at     as matecret_at'
                                    ,'yrendadjusts.salary_flg      as salary_flg'
                                    ,'yrendadjusts.remark_1        as remark_1'
                                    ,'yrendadjusts.remark_2        as remark_2'
                                    ,'yrendadjusts.cooperat        as cooperat'
                                    ,'yrendadjusts.refund_flg      as refund_flg'
                                    ,'yrendadjusts.declaration_flg as declaration_flg'
                                    ,'yrendadjusts.annual_flg      as annual_flg'
                                    ,'yrendadjusts.withhold_flg    as withhold_flg'
                                    ,'yrendadjusts.claim_flg       as claim_flg'
                                    ,'yrendadjusts.payment_flg     as payment_flg'

                                    ,'customers.id as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.business_code as business_code'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('yrendadjusts.custm_id', '=', 'customers.id');
                                    })

                                    ->where('yrendadjusts.organization_id','>=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('yrendadjusts.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('yrendadjusts.year', '=', $keyyear)
                                    ->sortable()
                                    ->orderBy('yrendadjusts.id', 'desc')
                                    ->paginate(300);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // yrendadjustsを取得
                $yrendadjusts = Yrendadjust::select(
                                    'yrendadjusts.id               as id'
                                    ,'yrendadjusts.organization_id as organization_id'
                                    ,'yrendadjusts.custm_id        as custm_id'
                                    ,'yrendadjusts.year            as year'
                                    ,'yrendadjusts.absence_flg     as absence_flg'
                                    ,'yrendadjusts.trustees_no     as trustees_no'
                                    ,'yrendadjusts.communica_flg   as communica_flg'
                                    ,'yrendadjusts.announce_at     as announce_at'
                                    ,'yrendadjusts.docinfor_at     as docinfor_at'
                                    ,'yrendadjusts.doccolle_at     as doccolle_at'
                                    ,'yrendadjusts.rrequest_at     as rrequest_at'
                                    ,'yrendadjusts.matecret_at     as matecret_at'
                                    ,'yrendadjusts.salary_flg      as salary_flg'
                                    ,'yrendadjusts.remark_1        as remark_1'
                                    ,'yrendadjusts.remark_2        as remark_2'
                                    ,'yrendadjusts.cooperat        as cooperat'
                                    ,'yrendadjusts.refund_flg      as refund_flg'
                                    ,'yrendadjusts.declaration_flg as declaration_flg'
                                    ,'yrendadjusts.annual_flg      as annual_flg'
                                    ,'yrendadjusts.withhold_flg    as withhold_flg'
                                    ,'yrendadjusts.claim_flg       as claim_flg'
                                    ,'yrendadjusts.payment_flg     as payment_flg'

                                    ,'customers.id as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.business_code as business_code'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('yrendadjusts.custm_id', '=', 'customers.id');
                                    })
                                    ->where('yrendadjusts.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('yrendadjusts.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('yrendadjusts.year', '=', $keyyear)
                                    ->sortable()
                                    ->orderBy('yrendadjusts.id', 'desc')
                                    ->paginate(300);
            }
        } else {
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // yrendadjustsを取得
                $yrendadjusts = Yrendadjust::where('organization_id','>=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(300);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // yrendadjustsを取得
                $yrendadjusts = Yrendadjust::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(300);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '04';
        // * 選択された年を取得
        $nowyear = $keyyear;
        $keyword2  = $keyword;

        $compacts = compact( 'userid','common_no','customers','yrendadjusts','nowyear','keyword2' );
        Log::info('yrendadjustr serch END');

        // return view('yrendadjust.input', ['yrendadjusts' => $yrendadjusts]);
        return view('yrendadjust.index', $compacts);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(Yrendadjust $yrendadjust, Request $request)
    {
        Log::info('yrendadjust serch_custom START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');
        $keyyear = $request->Input('year');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 日付or年が入力された
        if($keyword || $keyyear) {
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // yrendadjustsを取得
                $yrendadjusts = Yrendadjust::select(
                                    'yrendadjusts.id               as id'
                                    ,'yrendadjusts.organization_id as organization_id'
                                    ,'yrendadjusts.custm_id        as custm_id'
                                    ,'yrendadjusts.year            as year'
                                    ,'yrendadjusts.absence_flg     as absence_flg'
                                    ,'yrendadjusts.trustees_no     as trustees_no'
                                    ,'yrendadjusts.communica_flg   as communica_flg'
                                    ,'yrendadjusts.announce_at     as announce_at'
                                    ,'yrendadjusts.docinfor_at     as docinfor_at'
                                    ,'yrendadjusts.doccolle_at     as doccolle_at'
                                    ,'yrendadjusts.rrequest_at     as rrequest_at'
                                    ,'yrendadjusts.matecret_at     as matecret_at'
                                    ,'yrendadjusts.salary_flg      as salary_flg'
                                    ,'yrendadjusts.remark_1        as remark_1'
                                    ,'yrendadjusts.remark_2        as remark_2'
                                    ,'yrendadjusts.cooperat        as cooperat'
                                    ,'yrendadjusts.refund_flg      as refund_flg'
                                    ,'yrendadjusts.declaration_flg as declaration_flg'
                                    ,'yrendadjusts.annual_flg      as annual_flg'
                                    ,'yrendadjusts.withhold_flg    as withhold_flg'
                                    ,'yrendadjusts.claim_flg       as claim_flg'
                                    ,'yrendadjusts.payment_flg     as payment_flg'

                                    ,'customers.id as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.business_code as business_code'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('yrendadjusts.custm_id', '=', 'customers.id');
                                    })

                                    ->where('yrendadjusts.organization_id','>=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('yrendadjusts.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('yrendadjusts.year', '=', $keyyear)
                                    ->sortable()
                                    ->orderBy('yrendadjusts.id', 'desc')
                                    ->paginate(300);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // yrendadjustsを取得
                $yrendadjusts = Yrendadjust::select(
                                    'yrendadjusts.id               as id'
                                    ,'yrendadjusts.organization_id as organization_id'
                                    ,'yrendadjusts.custm_id        as custm_id'
                                    ,'yrendadjusts.year            as year'
                                    ,'yrendadjusts.absence_flg     as absence_flg'
                                    ,'yrendadjusts.trustees_no     as trustees_no'
                                    ,'yrendadjusts.communica_flg   as communica_flg'
                                    ,'yrendadjusts.announce_at     as announce_at'
                                    ,'yrendadjusts.docinfor_at     as docinfor_at'
                                    ,'yrendadjusts.doccolle_at     as doccolle_at'
                                    ,'yrendadjusts.rrequest_at     as rrequest_at'
                                    ,'yrendadjusts.matecret_at     as matecret_at'
                                    ,'yrendadjusts.salary_flg      as salary_flg'
                                    ,'yrendadjusts.remark_1        as remark_1'
                                    ,'yrendadjusts.remark_2        as remark_2'
                                    ,'yrendadjusts.cooperat        as cooperat'
                                    ,'yrendadjusts.refund_flg      as refund_flg'
                                    ,'yrendadjusts.declaration_flg as declaration_flg'
                                    ,'yrendadjusts.annual_flg      as annual_flg'
                                    ,'yrendadjusts.withhold_flg    as withhold_flg'
                                    ,'yrendadjusts.claim_flg       as claim_flg'
                                    ,'yrendadjusts.payment_flg     as payment_flg'

                                    ,'customers.id as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.business_code as business_code'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('yrendadjusts.custm_id', '=', 'customers.id');
                                    })
                                    ->where('yrendadjusts.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('yrendadjusts.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('yrendadjusts.year', '=', $keyyear)
                                    ->sortable()
                                    ->orderBy('yrendadjusts.id', 'desc')
                                    ->paginate(300);
            }
        } else {
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // yrendadjustsを取得
                $yrendadjusts = Yrendadjust::where('organization_id','>=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(300);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // yrendadjustsを取得
                $yrendadjusts = Yrendadjust::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(300);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '04';
        // * 選択された年を取得
        $nowyear = $keyyear;
        $keyword2  = $keyword;

        $compacts = compact( 'userid','common_no','customers','yrendadjusts','nowyear','keyword2' );
        Log::info('yrendadjustr serch_custom END');

        // return view('yrendadjust.index', ['yrendadjusts' => $yrendadjusts]);
        return view('yrendadjust.input', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
            'custm_id'          => [
                'required',
                Rule::unique('yrendadjusts')->whereNull('deleted_at')->ignore($id),
            ],
            'absence_flg'     => [
                                    'required',
                                ],


        ];

        $messages = [
            'custm_id.required'            => '会社名は入力必須項目です。',
            'custm_id.unique'              => 'その会社名は既に登録されています。',
            'absence_flg.required'         => '年調の有無は入力必須項目です。',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }

    /**
     *
     */
    public function get_validator2(Request $request,$id)
    {
        $rules   = [
            'custm_id'          => [
                'required',
            ],
            'absence_flg'     => [
                                    'required',
                                ],


        ];

        $messages = [
            'custm_id.required'            => '会社名は入力必須項目です。',
            'absence_flg.required'         => '年調の有無は入力必須項目です。',

        ];

        $validator2 = Validator::make($request->all(), $rules, $messages);

        return $validator2;
    }
}
