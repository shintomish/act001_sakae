<?php
namespace App\Http\Controllers;

use File;
use Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Spedelidate;
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

class SpedelidateController extends Controller
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
        Log::info('spedelidate index START');

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
            $spedelidates = Spedelidate::select(
                             'spedelidates.id               as id'
                            ,'spedelidates.organization_id  as organization_id'
                            ,'spedelidates.custm_id         as custm_id'
                            ,'spedelidates.year             as year'
                            ,'spedelidates.officecompe        as  officecompe'
                            ,'spedelidates.employee           as  employee'
                            ,'spedelidates.paymenttype        as  paymenttype'
                            ,'spedelidates.adept_flg          as  adept_flg'
                            ,'spedelidates.payslip_flg        as  payslip_flg'
                            ,'spedelidates.declaration_flg    as  declaration_flg'
                            ,'spedelidates.paydate_att        as  paydate_att'
                            ,'spedelidates.checklist          as  checklist'
                            ,'spedelidates.chaneg_flg         as  chaneg_flg'
                            ,'spedelidates.after_change       as  after_change'
                            ,'spedelidates.change_time        as  change_time'
                            ,'spedelidates.linkage_pay        as  linkage_pay'

                            ,'customers.id               as customers_id'
                            ,'customers.business_code    as business_code'
                            ,'customers.business_name    as business_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('spedelidates.custm_id', '=', 'customers.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('spedelidates.deleted_at')
                            ->where('spedelidates.year','=',$nowyear)
                            ->sortable('business_name','business_code')
                            ->orderBy('spedelidates.id', 'desc')
                            ->paginate(300);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

            $spedelidates = Spedelidate::select(
                            'spedelidates.id                as id'
                            ,'spedelidates.organization_id  as organization_id'
                            ,'spedelidates.custm_id         as custm_id'
                            ,'spedelidates.year             as year'

                            ,'spedelidates.officecompe        as  officecompe'
                            ,'spedelidates.employee           as  employee'
                            ,'spedelidates.paymenttype        as  paymenttype'
                            ,'spedelidates.adept_flg          as  adept_flg'
                            ,'spedelidates.payslip_flg        as  payslip_flg'
                            ,'spedelidates.declaration_flg    as  declaration_flg'
                            ,'spedelidates.paydate_att        as  paydate_att'
                            ,'spedelidates.checklist          as  checklist'
                            ,'spedelidates.chaneg_flg         as  chaneg_flg'
                            ,'spedelidates.after_change       as  after_change'
                            ,'spedelidates.change_time        as  change_time'
                            ,'spedelidates.linkage_pay        as  linkage_pay'

                            ,'customers.id               as customers_id'
                            ,'customers.business_code    as business_code'
                            ,'customers.business_name    as business_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('spedelidates.custm_id', '=', 'customers.id');
                            })
                            ->where('spedelidates.organization_id','=',$organization_id)
                            ->whereNull('customers.deleted_at')
                            ->whereNull('spedelidates.deleted_at')
                            ->where('spedelidates.year','=',$nowyear)
                            ->sortable()
                            ->paginate(300);
        }
        $common_no = '03';

        $keyword2  = null;

        $compacts = compact( 'userid','common_no','customers','spedelidates','nowyear','keyword2' );
        Log::info('spedelidate index END');
        return view( 'spedelidate.index', $compacts );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function input(Request $request)
    {
        Log::info('spedelidate input START');

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
            $spedelidates = Spedelidate::select(
                             'spedelidates.id               as id'
                            ,'spedelidates.organization_id  as organization_id'
                            ,'spedelidates.custm_id         as custm_id'
                            ,'spedelidates.year             as year'
                            ,'spedelidates.officecompe        as  officecompe'
                            ,'spedelidates.employee           as  employee'
                            ,'spedelidates.paymenttype        as  paymenttype'
                            ,'spedelidates.adept_flg          as  adept_flg'
                            ,'spedelidates.payslip_flg        as  payslip_flg'
                            ,'spedelidates.declaration_flg    as  declaration_flg'
                            ,'spedelidates.paydate_att        as  paydate_att'
                            ,'spedelidates.checklist          as  checklist'
                            ,'spedelidates.chaneg_flg         as  chaneg_flg'
                            ,'spedelidates.after_change       as  after_change'
                            ,'spedelidates.change_time        as  change_time'
                            ,'spedelidates.linkage_pay        as  linkage_pay'

                            ,'customers.id               as customers_id'
                            ,'customers.business_code    as business_code'
                            ,'customers.business_name    as business_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('spedelidates.custm_id', '=', 'customers.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('spedelidates.deleted_at')
                            ->where('spedelidates.year','=',$nowyear)
                            ->sortable()
                            ->paginate(300);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

            $spedelidates = Spedelidate::select(
                            'spedelidates.id                as id'
                            ,'spedelidates.organization_id  as organization_id'
                            ,'spedelidates.custm_id         as custm_id'
                            ,'spedelidates.year             as year'

                            ,'spedelidates.officecompe        as  officecompe'
                            ,'spedelidates.employee           as  employee'
                            ,'spedelidates.paymenttype        as  paymenttype'
                            ,'spedelidates.adept_flg          as  adept_flg'
                            ,'spedelidates.payslip_flg        as  payslip_flg'
                            ,'spedelidates.declaration_flg    as  declaration_flg'
                            ,'spedelidates.paydate_att        as  paydate_att'
                            ,'spedelidates.checklist          as  checklist'
                            ,'spedelidates.chaneg_flg         as  chaneg_flg'
                            ,'spedelidates.after_change       as  after_change'
                            ,'spedelidates.change_time        as  change_time'
                            ,'spedelidates.linkage_pay        as  linkage_pay'

                            ,'customers.id               as customers_id'
                            ,'customers.business_code    as business_code'
                            ,'customers.business_name    as business_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('spedelidates.custm_id', '=', 'customers.id');
                            })
                            ->where('spedelidates.organization_id','=',$organization_id)
                            ->whereNull('customers.deleted_at')
                            ->whereNull('spedelidates.deleted_at')
                            ->where('spedelidates.year','=',$nowyear)
                            ->sortable()
                            ->paginate(300);
        }
        $common_no = '03';

        $keyword2  = null;

        $compacts = compact( 'userid','common_no','customers','spedelidates','nowyear','keyword2' );
        Log::info('spedelidate input END');
        return view( 'spedelidate.input', $compacts );
    }

    /**
     * [webapi]spedelidateテーブルの更新
     */
    public function update_api(Request $request)
    {
        Log::info('update_api spedelidate START');

        // Log::debug('update_api request = ' .print_r($request->all(),true));
        $id = $request->input('id');

        // $organization      = $this->auth_user_organization();
        $officecompe      = $request->input('officecompe');     //'役員報酬'
        $employee         = $request->input('employee');        //'従業員'
        $paymenttype      = $request->input('paymenttype');     //'納付種別'
        $adept_flg        = $request->input('adept_flg');       //達人フラグ
        $payslip_flg      = $request->input('payslip_flg');     //納付書作成
        $declaration_flg  = $request->input('declaration_flg'); //0円納付申告
        $paydate_att      = $request->input('paydate_att');     //支払日注意
        $checklist        = $request->input('checklist');       //確認事項
        $chaneg_flg       = $request->input('chaneg_flg');      //役員報酬変更 2022/05/27
        $after_change     = $request->input('after_change');    //変更後
        $change_time      = $request->input('change_time');     //変更時期
        $linkage_pay      = $request->input('linkage_pay');     //納付書データの連携

        // Log::debug('organization_id   : ' . $organization->id);
        // Log::debug('proc_date         : ' . $proc_date);
        // Log::debug('attach_doc        : ' . $attach_doc);

        $counts = array();
        $update = [];
        if( $request->exists('officecompe')        ) $update['officecompe']      = $request->input('officecompe');
        if( $request->exists('employee')           ) $update['employee']         = $request->input('employee');
        if( $request->exists('paymenttype')        ) $update['paymenttype']      = $request->input('paymenttype');
        if( $request->exists('adept_flg')          ) $update['adept_flg']        = $request->input('adept_flg');
        if( $request->exists('payslip_flg')        ) $update['payslip_flg']      = $request->input('payslip_flg');
        if( $request->exists('declaration_flg')    ) $update['declaration_flg']  = $request->input('declaration_flg');
        if( $request->exists('paydate_att')        ) $update['paydate_att']      = $request->input('paydate_att');
        if( $request->exists('checklist')          ) $update['checklist']        = $request->input('checklist');
        if( $request->exists('after_change')       ) $update['after_change']     = $request->input('after_change');
        //役員報酬変更 2022/05/27
        if( $request->exists('chaneg_flg')         ) $update['chaneg_flg']       = $request->input('chaneg_flg');
        if( $request->exists('change_time')        ) $update['change_time']      = $request->input('change_time');
        if( $request->exists('linkage_pay')        ) $update['checklist']        = $request->input('linkage_pay');

        $update['updated_at'] = date('Y-m-d H:i:s');
        // Log::debug('update_api update : ' . print_r($update,true));

        $status = array();
        DB::beginTransaction();
        Log::info('update_api spedelidate beginTransaction - start');
        try{
            // 更新処理
            Spedelidate::where( 'id', $id )->update($update);

            $status = array( 'error_code' => 0, 'message'  => 'Your data has been changed!' );

            DB::commit();
            Log::info('update_api spedelidate beginTransaction - end');
        }
        catch(Exception $e){
            Log::error('update_api spedelidate exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('update_api spedelidate beginTransaction - end(rollback)');
            echo "エラー：" . $e->getMessage();
            $status = array( 'error_code' => 501, 'message'  => $e->getMessage() );
        }

        Log::info('update_api spedelidate END');
        return response()->json([ compact('status','counts') ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('Spedelidate create START');

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

        // spedelidatesを取得
        $spedelidates = DB::table('spedelidates')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

        $compacts = compact( 'userid','customers','spedelidates','organization_id','nowyear' );

        Log::info('spedelidate create END');
        return view( 'spedelidate.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('spedelidate store START');

        $organization = $this->auth_user_organization();

        $request->merge( ['organization_id'=> $organization->id] );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('spedelidate/create')->withErrors($validator)->withInput();
        }

// Log::debug('spedelidates store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - spedelidate store start');
        try {
            Spedelidate::create($request->all());
            DB::commit();

            Log::info('beginTransaction - spedelidate store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - spedelidate store end(rollback)');
        }

        Log::info('spedelidate store END');
        // return redirect()->route('customer.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('spedelidate.input');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('spedelidate show START');
        Log::info('spedelidate show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('spedelidate edit START');

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

        $spedelidate = Spedelidate::find($id);

        $compacts = compact( 'spedelidate', 'customers', 'organization_id' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('spedelidate edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('spedelidate.edit', $compacts );
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
        Log::info('spedelidate update START');

        $validator = $this->get_validator2($request,$id);
        if ($validator->fails()) {
            return redirect('spedelidate/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $spedelidate = Spedelidate::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - spedelidate update start');
        try {
                $spedelidate->year              = $request->year;
                $spedelidate->officecompe       = $request->officecompe;
                $spedelidate->employee          = $request->employee;
                $spedelidate->paymenttype       = $request->paymenttype;
                $spedelidate->adept_flg         = $request->adept_flg;
                $spedelidate->payslip_flg       = $request->payslip_flg;
                $spedelidate->declaration_flg   = $request->declaration_flg;
                $spedelidate->paydate_att       = $request->paydate_att;
                $spedelidate->checklist         = $request->checklist;
                $spedelidate->chaneg_flg        = $request->chaneg_flg;
                $spedelidate->after_change      = $request->after_change;
                $spedelidate->change_time       = $request->change_time;
                $spedelidate->linkage_pay       = $request->linkage_pay;
                $spedelidate->updated_at        = now();

                $result = $spedelidate->save();

                // Log::debug('spedelidate update = ' . $spedelidate);

                DB::commit();
                Log::info('beginTransaction - spedelidate update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - spedelidate update end(rollback)');
        }

        Log::info('spedelidate update END');
        // return redirect()->route('user.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('spedelidate.input');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('spedelidate destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - spedelidate destroy start');
        //return redirect(route('customer.index'))->with('msg_danger', '許可されていない操作です');

        try {
            // customer::where('id', $id)->delete();
            $spedelidate = Spedelidate::find($id);
            $spedelidate->deleted_at     = now();
            $result = $spedelidate->save();
            DB::commit();
            Log::info('beginTransaction - spedelidate destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - spedelidate destroy end(rollback)');
        }

        Log::info('spedelidate destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('spedelidate.input');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Spedelidate $spedelidate, Request $request)
    {
        Log::info('spedelidate serch START');

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
                // spedelidatesを取得
                $spedelidates = Spedelidate::select(
                                    'spedelidates.id               as id'
                                    ,'spedelidates.organization_id as organization_id'
                                    ,'spedelidates.custm_id        as custm_id'
                                    ,'spedelidates.year            as year'

                                    ,'spedelidates.officecompe        as  officecompe'
                                    ,'spedelidates.employee           as  employee'
                                    ,'spedelidates.paymenttype        as  paymenttype'
                                    ,'spedelidates.adept_flg          as  adept_flg'
                                    ,'spedelidates.payslip_flg        as  payslip_flg'
                                    ,'spedelidates.declaration_flg    as  declaration_flg'
                                    ,'spedelidates.paydate_att        as  paydate_att'
                                    ,'spedelidates.checklist          as  checklist'
                                    ,'spedelidates.chaneg_flg         as  chaneg_flg'
                                    ,'spedelidates.after_change       as  after_change'
                                    ,'spedelidates.change_time        as  change_time'
                                    ,'spedelidates.linkage_pay        as  linkage_pay'

                                    ,'customers.id            as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.business_code as business_code'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('spedelidates.custm_id', '=', 'customers.id');
                                    })

                                    ->where('spedelidates.organization_id','>=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('spedelidates.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('spedelidates.year', '=', $keyyear)
                                    ->sortable()
                                    ->paginate(300);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // spedelidatesを取得
                $spedelidates = Spedelidate::select(
                                    'spedelidates.id               as id'
                                    ,'spedelidates.organization_id as organization_id'
                                    ,'spedelidates.custm_id        as custm_id'
                                    ,'spedelidates.year            as year'

                                    ,'spedelidates.officecompe        as  officecompe'
                                    ,'spedelidates.employee           as  employee'
                                    ,'spedelidates.paymenttype        as  paymenttype'
                                    ,'spedelidates.adept_flg          as  adept_flg'
                                    ,'spedelidates.payslip_flg        as  payslip_flg'
                                    ,'spedelidates.declaration_flg    as  declaration_flg'
                                    ,'spedelidates.paydate_att        as  paydate_att'
                                    ,'spedelidates.checklist          as  checklist'
                                    ,'spedelidates.chaneg_flg         as  chaneg_flg'
                                    ,'spedelidates.after_change       as  after_change'
                                    ,'spedelidates.change_time        as  change_time'
                                    ,'spedelidates.linkage_pay        as  linkage_pay'

                                    ,'customers.id            as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.business_code as business_code'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('spedelidates.custm_id', '=', 'customers.id');
                                    })
                                    ->where('spedelidates.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('spedelidates.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('spedelidates.year', '=', $keyyear)
                                    ->sortable()
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
                // spedelidatesを取得
                $spedelidates = Spedelidate::where('organization_id','>=',$organization_id)
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
                // spedelidatesを取得
                $spedelidates = Spedelidate::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(300);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '03';
        // * 選択された年を取得
        $nowyear   = $keyyear;
        $keyword2  = $keyword;

        // Log::debug('spedelidaters store $spedelidaters = ' . print_r($spedelidaters, true));
        $compacts = compact( 'userid','common_no','customers','spedelidates','nowyear','keyword2' );
        Log::info('spedelidater serch END');

        // return view('spedelidate.index', ['spedelidates' => $spedelidates]);
        return view('spedelidate.index', $compacts);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(Spedelidate $spedelidate, Request $request)
    {
        Log::info('spedelidate serch_custom START');

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
                // spedelidatesを取得
                $spedelidates = Spedelidate::select(
                                    'spedelidates.id               as id'
                                    ,'spedelidates.organization_id as organization_id'
                                    ,'spedelidates.custm_id        as custm_id'
                                    ,'spedelidates.year            as year'

                                    ,'spedelidates.officecompe        as  officecompe'
                                    ,'spedelidates.employee           as  employee'
                                    ,'spedelidates.paymenttype        as  paymenttype'
                                    ,'spedelidates.adept_flg          as  adept_flg'
                                    ,'spedelidates.payslip_flg        as  payslip_flg'
                                    ,'spedelidates.declaration_flg    as  declaration_flg'
                                    ,'spedelidates.paydate_att        as  paydate_att'
                                    ,'spedelidates.checklist          as  checklist'
                                    ,'spedelidates.chaneg_flg         as  chaneg_flg'
                                    ,'spedelidates.after_change       as  after_change'
                                    ,'spedelidates.change_time        as  change_time'
                                    ,'spedelidates.linkage_pay        as  linkage_pay'

                                    ,'customers.id            as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.business_code as business_code'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('spedelidates.custm_id', '=', 'customers.id');
                                    })

                                    ->where('spedelidates.organization_id','>=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('spedelidates.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('spedelidates.year', '=', $keyyear)
                                    ->sortable()
                                    ->paginate(300);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // spedelidatesを取得
                $spedelidates = Spedelidate::select(
                                    'spedelidates.id               as id'
                                    ,'spedelidates.organization_id as organization_id'
                                    ,'spedelidates.custm_id        as custm_id'
                                    ,'spedelidates.year            as year'

                                    ,'spedelidates.officecompe        as  officecompe'
                                    ,'spedelidates.employee           as  employee'
                                    ,'spedelidates.paymenttype        as  paymenttype'
                                    ,'spedelidates.adept_flg          as  adept_flg'
                                    ,'spedelidates.payslip_flg        as  payslip_flg'
                                    ,'spedelidates.declaration_flg    as  declaration_flg'
                                    ,'spedelidates.paydate_att        as  paydate_att'
                                    ,'spedelidates.checklist          as  checklist'
                                    ,'spedelidates.chaneg_flg         as  chaneg_flg'
                                    ,'spedelidates.after_change       as  after_change'
                                    ,'spedelidates.change_time        as  change_time'
                                    ,'spedelidates.linkage_pay        as  linkage_pay'

                                    ,'customers.id            as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.business_code as business_code'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('spedelidates.custm_id', '=', 'customers.id');
                                    })
                                    ->where('spedelidates.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('spedelidates.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('spedelidates.year', '=', $keyyear)
                                    ->sortable()
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
                // spedelidatesを取得
                $spedelidates = Spedelidate::where('organization_id','>=',$organization_id)
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
                // spedelidatesを取得
                $spedelidates = Spedelidate::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(300);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '03';
        // * 選択された年を取得
        $nowyear   = $keyyear;
        $keyword2  = $keyword;

        // Log::debug('spedelidaters store $spedelidaters = ' . print_r($spedelidaters, true));
        $compacts = compact( 'userid','common_no','customers','spedelidates','nowyear','keyword2' );
        Log::info('spedelidater serch_custom END');

        // return view('spedelidate.index', ['spedelidates' => $spedelidates]);
        return view('spedelidate.input', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
            'custm_id'          => [
                'required',
                Rule::unique('spedelidates')->whereNull('deleted_at')->ignore($id),
            ],
            'officecompe'       => ['required',],
            'employee'          => ['required',],
        ];

        $messages = [
            'custm_id.required'            => '会社名は入力必須項目です。',
            'custm_id.unique'              => 'その会社名は既に登録されています。',
            'officecompe.required'         => '役員報酬は入力必須項目です。',
            'employee.required'            => '従業員は入力必須項目です。',

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
            'officecompe'       => ['required',],
            'employee'          => ['required',],
        ];

        $messages = [
            'custm_id.required'            => '会社名は入力必須項目です。',
            'officecompe.required'         => '役員報酬は入力必須項目です。',
            'employee.required'            => '従業員は入力必須項目です。',

        ];

        $validator2 = Validator::make($request->all(), $rules, $messages);

        return $validator2;
    }

}
