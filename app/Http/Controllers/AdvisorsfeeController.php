<?php
namespace App\Http\Controllers;

use File;
use Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Advisorsfee;
use App\Models\Progrecheck;
use App\Models\Schedule;
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

class AdvisorsfeeController extends Controller
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
        Log::info('advisorsfee index START');

        // 2023/09/22
        // ログインユーザーのユーザー情報を取得する
        $user    = $this->auth_user_info();
        $user_id = $user->id;
        
        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
            $advisorsfees = Advisorsfee::select(
                             'advisorsfees.id               as id'
                            ,'advisorsfees.organization_id  as organization_id'
                            ,'advisorsfees.custm_id         as custm_id'
                            ,'advisorsfees.year             as year'
                            ,'advisorsfees.advisor_fee      as advisor_fee'
                            ,'advisorsfees.fee_01        as fee_01'
                            ,'advisorsfees.fee_02        as fee_02'
                            ,'advisorsfees.fee_03        as fee_03'
                            ,'advisorsfees.fee_04        as fee_04'
                            ,'advisorsfees.fee_05        as fee_05'
                            ,'advisorsfees.fee_06        as fee_06'
                            ,'advisorsfees.fee_07        as fee_07'
                            ,'advisorsfees.fee_08        as fee_08'
                            ,'advisorsfees.fee_09        as fee_09'
                            ,'advisorsfees.fee_10        as fee_10'
                            ,'advisorsfees.fee_11        as fee_11'
                            ,'advisorsfees.fee_12        as fee_12'

                            ,'customers.id                  as customers_id'
                            ,'customers.business_name       as business_name'
                            ,'customers.individual_class    as individual_class'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('advisorsfees.custm_id', '=', 'customers.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('advisorsfees.deleted_at')
                            ->where('customers.year','=',$nowyear)
                            ->orderBy('customers.business_name', 'asc')
                            ->orderBy('customers.individual_class', 'asc')
                    ->sortable()
                            ->paginate(500);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)                            // 削除されていない
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

            $advisorsfees = Advisorsfee::select(
                             'advisorsfees.id               as id'
                            ,'advisorsfees.organization_id  as organization_id'
                            ,'advisorsfees.custm_id         as custm_id'
                            ,'advisorsfees.year             as year'
                            ,'advisorsfees.advisor_fee      as advisor_fee'
                            ,'advisorsfees.fee_01        as fee_01'
                            ,'advisorsfees.fee_02        as fee_02'
                            ,'advisorsfees.fee_03        as fee_03'
                            ,'advisorsfees.fee_04        as fee_04'
                            ,'advisorsfees.fee_05        as fee_05'
                            ,'advisorsfees.fee_06        as fee_06'
                            ,'advisorsfees.fee_07        as fee_07'
                            ,'advisorsfees.fee_08        as fee_08'
                            ,'advisorsfees.fee_09        as fee_09'
                            ,'advisorsfees.fee_10        as fee_10'
                            ,'advisorsfees.fee_11        as fee_11'
                            ,'advisorsfees.fee_12        as fee_12'

                            ,'customers.id                  as customers_id'
                            ,'customers.business_name       as business_name'
                            ,'customers.individual_class    as individual_class'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('advisorsfees.custm_id', '=', 'customers.id');
                            })
                            ->where('advisorsfees.organization_id','=',$organization_id)
                            ->whereNull('customers.deleted_at')
                            ->whereNull('advisorsfees.deleted_at')
                            ->where('advisorsfees.year','=',$nowyear)
                            ->orderBy('customers.business_name', 'asc')
                            ->orderBy('customers.individual_class', 'asc')
                    ->sortable()
                            ->paginate(500);
        }
        $common_no = '06';

        $keyword2  = null;

        // 2023/09/22
        $userid  = $user_id;

        $compacts = compact( 'userid','common_no','advisorsfees', 'customers','nowyear','keyword2' );
        Log::info('advisorsfee index END');
        return view( 'advisorsfee.index', $compacts );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function input(Request $request)
    {
        Log::info('advisorsfee input START');

        // 2023/09/22
        // ログインユーザーのユーザー情報を取得する
        $user    = $this->auth_user_info();
        $user_id = $user->id;
        
        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // ->where('active_cancel','!=', 3)
                            ->whereNull('deleted_at')
                            ->get();
            $advisorsfees = Advisorsfee::select(
                             'advisorsfees.id               as id'
                            ,'advisorsfees.organization_id  as organization_id'
                            ,'advisorsfees.custm_id         as custm_id'
                            ,'advisorsfees.year             as year'
                            ,'advisorsfees.advisor_fee      as advisor_fee'
                            ,'advisorsfees.fee_01        as fee_01'
                            ,'advisorsfees.fee_02        as fee_02'
                            ,'advisorsfees.fee_03        as fee_03'
                            ,'advisorsfees.fee_04        as fee_04'
                            ,'advisorsfees.fee_05        as fee_05'
                            ,'advisorsfees.fee_06        as fee_06'
                            ,'advisorsfees.fee_07        as fee_07'
                            ,'advisorsfees.fee_08        as fee_08'
                            ,'advisorsfees.fee_09        as fee_09'
                            ,'advisorsfees.fee_10        as fee_10'
                            ,'advisorsfees.fee_11        as fee_11'
                            ,'advisorsfees.fee_12        as fee_12'

                            ,'customers.id                  as customers_id'
                            ,'customers.business_name       as business_name'
                            ,'customers.individual_class    as individual_class'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('advisorsfees.custm_id', '=', 'customers.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('advisorsfees.deleted_at')
                            ->where('advisorsfees.year','=',$nowyear)
                            ->orderBy('customers.individual_class', 'asc')
                            ->orderBy('customers.business_name', 'asc')
                            ->sortable()
                            ->paginate(500);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // ->where('active_cancel','!=', 3)                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

            $advisorsfees = Advisorsfee::select(
                             'advisorsfees.id               as id'
                            ,'advisorsfees.organization_id  as organization_id'
                            ,'advisorsfees.custm_id         as custm_id'
                            ,'advisorsfees.year             as year'
                            ,'advisorsfees.advisor_fee      as advisor_fee'
                            ,'advisorsfees.fee_01        as fee_01'
                            ,'advisorsfees.fee_02        as fee_02'
                            ,'advisorsfees.fee_03        as fee_03'
                            ,'advisorsfees.fee_04        as fee_04'
                            ,'advisorsfees.fee_05        as fee_05'
                            ,'advisorsfees.fee_06        as fee_06'
                            ,'advisorsfees.fee_07        as fee_07'
                            ,'advisorsfees.fee_08        as fee_08'
                            ,'advisorsfees.fee_09        as fee_09'
                            ,'advisorsfees.fee_10        as fee_10'
                            ,'advisorsfees.fee_11        as fee_11'
                            ,'advisorsfees.fee_12        as fee_12'

                            ,'customers.id                  as customers_id'
                            ,'customers.business_name       as business_name'
                            ,'customers.individual_class    as individual_class'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('advisorsfees.custm_id', '=', 'customers.id');
                            })
                            ->where('advisorsfees.organization_id','=',$organization_id)
                            ->whereNull('customers.deleted_at')
                            ->whereNull('advisorsfees.deleted_at')
                            ->where('advisorsfees.year','=',$nowyear)
                            ->orderBy('customers.individual_class', 'asc')
                            ->orderBy('customers.business_name', 'asc')
                            ->sortable()
                            ->paginate(500);
        }
        $common_no = '06';

        $keyword2  = null;

        // 2023/09/22
        $userid  = $user_id;

        //今月の月を取得
        $nowmonth = intval($this->get_now_month());

        Log::debug('advisorsfee input nowyear = ' .print_r($nowyear,true));

        $compacts = compact( 'userid','common_no','advisorsfees', 'customers','nowyear','keyword2','nowmonth' );
        Log::info('advisorsfee input END');
        return view( 'advisorsfee.input', $compacts );
    }

    /**
     * [webapi]advisorsfeeテーブルの更新
     */
    public function update_api(Request $request)
    {
        Log::info('update_api advisorsfee START');

        // Log::debug('update_api request = ' .print_r($request->all(),true));
        $id = $request->input('id');

        // $organization      = $this->auth_user_organization();
        $advisor_fee    = $request->input('advisor_fee');   //'顧問料金'
        $fee_01         = $request->input('fee_01');        //'顧問料01'
        $fee_02         = $request->input('fee_02');        //'顧問料02'
        $fee_03         = $request->input('fee_03');        //'顧問料03'
        $fee_04         = $request->input('fee_04');        //'顧問料04'
        $fee_05         = $request->input('fee_05');        //'顧問料05'
        $fee_06         = $request->input('fee_06');        //'顧問料06'
        $fee_07         = $request->input('fee_07');        //'顧問料07'
        $fee_08         = $request->input('fee_08');        //'顧問料08'
        $fee_09         = $request->input('fee_09');        //'顧問料09'
        $fee_10         = $request->input('fee_10');        //'顧問料10'
        $fee_11         = $request->input('fee_11');        //'顧問料11'
        $fee_12         = $request->input('fee_12');        //'顧問料12'

        // Log::debug('organization_id   : ' . $organization->id);
        // Log::debug('proc_date         : ' . $proc_date);
        // Log::debug('attach_doc        : ' . $attach_doc);

        $counts = array();
        $update = [];
        if( $request->exists('advisor_fee')   ) $update['advisor_fee']  = $request->input('advisor_fee');
        if( $request->exists('fee_01')        ) $update['fee_01']       = $request->input('fee_01');
        if( $request->exists('fee_02')        ) $update['fee_02']       = $request->input('fee_02');
        if( $request->exists('fee_03')        ) $update['fee_03']       = $request->input('fee_03');
        if( $request->exists('fee_04')        ) $update['fee_04']       = $request->input('fee_04');
        if( $request->exists('fee_05')        ) $update['fee_05']       = $request->input('fee_05');
        if( $request->exists('fee_06')        ) $update['fee_06']       = $request->input('fee_06');
        if( $request->exists('fee_07')        ) $update['fee_07']       = $request->input('fee_07');
        if( $request->exists('fee_08')        ) $update['fee_08']       = $request->input('fee_08');
        if( $request->exists('fee_09')        ) $update['fee_09']       = $request->input('fee_09');
        if( $request->exists('fee_10')        ) $update['fee_10']       = $request->input('fee_10');
        if( $request->exists('fee_11')        ) $update['fee_11']       = $request->input('fee_11');
        if( $request->exists('fee_12')        ) $update['fee_12']       = $request->input('fee_12');

        $update['updated_at'] = date('Y-m-d H:i:s');
        // Log::debug('update_api update : ' . print_r($update,true));

        $status = array();
        DB::beginTransaction();
        Log::info('update_api advisorsfee beginTransaction - start');
        try{
            // 更新処理
            Advisorsfee::where( 'id', $id )->update($update);

            if( $request->exists('advisor_fee') ) {
                $advisorsfees = Advisorsfee::find( $id );
//  Log::debug('update_api $advisorsfees->custm_id  = ' . $advisorsfees->custm_id);
                $customer = Customer::find($advisorsfees->custm_id);
                $customer->advisor_fee      = $request->advisor_fee;
                $customer->updated_at       = now();
                $result = $customer->save();
            }

            $status = array( 'error_code' => 0, 'message'  => 'Your data has been changed!' );

            DB::commit();
            Log::info('update_api advisorsfee beginTransaction - end');
        }
        catch(Exception $e){
            Log::error('update_api advisorsfee exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('update_api advisorsfee beginTransaction - end(rollback)');
            echo "エラー：" . $e->getMessage();
            $status = array( 'error_code' => 501, 'message'  => $e->getMessage() );
        }

        Log::info('update_api advisorsfee END');
        return response()->json([ compact('status','counts') ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('advisorsfee create START');

        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;
        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)                            // 削除されていない
                                ->whereNull('deleted_at')
                                ->orderby('id','desc')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)                            // 削除されていない
                                ->whereNull('deleted_at')
                                ->orderby('id','desc')
                                ->get();
        }

        // advisorsfeesを取得
        $advisorsfees = DB::table('advisorsfees')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

        $compacts = compact( 'customers','advisorsfees','organization_id','nowyear' );

        Log::info('advisorsfee create END');
        return view( 'advisorsfee.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('advisorsfee store START');

        $organization = $this->auth_user_organization();

        $request->merge( ['organization_id'=> $organization->id] );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('advisorsfee/create')->withErrors($validator)->withInput();
        }

// Log::debug('advisorsfees store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - advisorsfee store start');
        try {
            Advisorsfee::create($request->all());

            $customer = Customer::find($request->custm_id);
            $customer->advisor_fee      = $request->advisor_fee;
            $customer->updated_at       = now();
            $result = $customer->save();

            Log::info('beginTransaction - advisorsfee customer end(commit)');

            DB::commit();

            Log::info('beginTransaction - advisorsfee store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - advisorsfee store end(rollback)');
        }

        Log::info('advisorsfee store END');
        // return redirect()->route('customer.input')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('advisorsfee.input');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('advisorsfee show START');
        Log::info('advisorsfee show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('advisorsfee edit START');

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
                                ->where('active_cancel','!=', 3)
                                // 2023/09/23
                                ->orderBy('individual_class', 'asc')
                                ->orderBy('business_name', 'asc')
                                // 削除されていない
                                ->whereNull('deleted_at')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                // 2023/09/23
                                ->orderBy('individual_class', 'asc')
                                ->orderBy('business_name', 'asc')
                                // 削除されていない
                                ->whereNull('deleted_at')
                                ->get();
        }

        $advisorsfee = Advisorsfee::find($id);

        $compacts = compact( 'advisorsfee', 'customers', 'organization_id' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('advisorsfee edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('advisorsfee.edit', $compacts );
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
        Log::info('advisorsfee update START');

        $validator = $this->get_validator2($request,$id);
        if ($validator->fails()) {
            return redirect('advisorsfee/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $advisorsfee = Advisorsfee::find($id);
        $customer = Customer::find($request->custm_id);

        DB::beginTransaction();
        Log::info('beginTransaction - advisorsfee update start');
        try {
                $advisorsfee->year          = $request->year;
                $advisorsfee->advisor_fee   = $request->advisor_fee;
                $advisorsfee->fee_01        = $request->fee_01;
                $advisorsfee->fee_02        = $request->fee_02;
                $advisorsfee->fee_03        = $request->fee_03;
                $advisorsfee->fee_04        = $request->fee_04;
                $advisorsfee->fee_05        = $request->fee_05;
                $advisorsfee->fee_06        = $request->fee_06;
                $advisorsfee->fee_07        = $request->fee_07;
                $advisorsfee->fee_08        = $request->fee_08;
                $advisorsfee->fee_09        = $request->fee_09;
                $advisorsfee->fee_10        = $request->fee_10;
                $advisorsfee->fee_11        = $request->fee_11;
                $advisorsfee->fee_12        = $request->fee_12;
                $advisorsfee->updated_at    = now();
                $result = $advisorsfee->save();

                $customer->advisor_fee      = $request->advisor_fee;
                $customer->updated_at       = now();
                $result = $customer->save();
                // Log::debug('advisorsfee update = ' . $advisorsfee);

                DB::commit();
                Log::info('beginTransaction - advisorsfee update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - advisorsfee update end(rollback)');
        }

        Log::info('advisorsfee update END');
        // return redirect()->route('user.input')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('advisorsfee.input');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('advisorsfee destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - advisorsfee destroy start');
        //return redirect(route('customer.index'))->with('msg_danger', '許可されていない操作です');

        try {
            // customer::where('id', $id)->delete();
            $advisorsfee = Advisorsfee::find($id);
            $advisorsfee->deleted_at     = now();
            $result = $advisorsfee->save();
            DB::commit();
            Log::info('beginTransaction - advisorsfee destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - advisorsfee destroy end(rollback)');
        }

        Log::info('advisorsfee destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('advisorsfee.input');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(Advisorsfee $advisorsfee, Request $request)
    {
        Log::info('advisorsfee serch_custom START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');
        $keyyear = $request->Input('year');

        // 2023/09/22
        // ログインユーザーのユーザー情報を取得する
        $user    = $this->auth_user_info();
        $user_id = $user->id;

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得
        $nowyear    = intval($this->get_now_year());
        // 日付or年が入力された
        if($keyword || $keyyear) {
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    ->get();
                // advisorsfeesを取得
                $advisorsfees = Advisorsfee::select(
                                    'advisorsfees.id                as id'
                                    ,'advisorsfees.organization_id  as organization_id'
                                    ,'advisorsfees.custm_id         as custm_id'
                                    ,'advisorsfees.year             as year'
                                    ,'advisorsfees.advisor_fee      as advisor_fee'
		                            ,'advisorsfees.fee_01        as fee_01'
		                            ,'advisorsfees.fee_02        as fee_02'
		                            ,'advisorsfees.fee_03        as fee_03'
		                            ,'advisorsfees.fee_04        as fee_04'
		                            ,'advisorsfees.fee_05        as fee_05'
		                            ,'advisorsfees.fee_06        as fee_06'
		                            ,'advisorsfees.fee_07        as fee_07'
		                            ,'advisorsfees.fee_08        as fee_08'
		                            ,'advisorsfees.fee_09        as fee_09'
		                            ,'advisorsfees.fee_10        as fee_10'
		                            ,'advisorsfees.fee_11        as fee_11'
		                            ,'advisorsfees.fee_12        as fee_12'

                                    ,'customers.id as customers_id'
                                    ,'customers.business_name as business_name'
                                    ,'customers.individual_class    as individual_class'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('advisorsfees.custm_id', '=', 'customers.id');
                                    })

                                    ->where('advisorsfees.organization_id','>=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('advisorsfees.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('advisorsfees.year', '=', $keyyear)
                                    ->orderBy('customers.individual_class', 'asc')
                                    ->orderBy('customers.business_name', 'asc')
                                    ->sortable()
                                    ->paginate(500);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    ->get();
                // advisorsfeesを取得
                $advisorsfees = Advisorsfee::select(
                                    'advisorsfees.id                as id'
                                    ,'advisorsfees.organization_id  as organization_id'
                                    ,'advisorsfees.custm_id         as custm_id'
                                    ,'advisorsfees.year             as year'
                                    ,'advisorsfees.advisor_fee      as advisor_fee'
		                            ,'advisorsfees.fee_01        as fee_01'
		                            ,'advisorsfees.fee_02        as fee_02'
		                            ,'advisorsfees.fee_03        as fee_03'
		                            ,'advisorsfees.fee_04        as fee_04'
		                            ,'advisorsfees.fee_05        as fee_05'
		                            ,'advisorsfees.fee_06        as fee_06'
		                            ,'advisorsfees.fee_07        as fee_07'
		                            ,'advisorsfees.fee_08        as fee_08'
		                            ,'advisorsfees.fee_09        as fee_09'
		                            ,'advisorsfees.fee_10        as fee_10'
		                            ,'advisorsfees.fee_11        as fee_11'
		                            ,'advisorsfees.fee_12        as fee_12'

                                    ,'customers.id                  as customers_id'
                                    ,'customers.business_name       as business_name'
                                    ,'customers.individual_class    as individual_class'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('advisorsfees.custm_id', '=', 'customers.id');
                                    })
                                    ->where('advisorsfees.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('advisorsfees.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('advisorsfees.year', '=', $keyyear)
                                    ->orderBy('customers.individual_class', 'asc')
                                    ->orderBy('customers.business_name', 'asc')
                                    ->sortable()
                                    ->paginate(500);
            }
        } else {
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // advisorsfeesを取得
                $advisorsfees = Advisorsfee::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(3);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // advisorsfeesを取得
                $advisorsfees = Advisorsfee::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(3);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '06';
        // * 選択された年を取得
        $nowyear   = $keyyear;
        $keyword2  = $keyword;

        // 2023/09/22
        $userid = $user_id;

        // 2023/09/22
        //今月の月を取得
        $nowmonth = intval($this->get_now_month());

        // Log::debug('advisorsfeers store $advisorsfeers = ' . print_r($advisorsfeers, true));
        $compacts = compact( 'userid','common_no','customers','advisorsfees','nowyear','keyword2','nowmonth' );
        Log::info('advisorsfeer serch_custom END');

        // return view('advisorsfee.input', ['advisorsfees' => $advisorsfees]);
        return view('advisorsfee.input', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
                'custm_id'          => [
                                        'required',
                                        Rule::unique('advisorsfees')->whereNull('deleted_at')->ignore($id),
                                    ],
                'advisor_fee'       => ['required',],
                'fee_01'            => ['required',],
                'fee_02'            => ['required',],
                'fee_03'            => ['required',],
                'fee_04'            => ['required',],
                'fee_05'            => ['required',],
                'fee_06'            => ['required',],
                'fee_07'            => ['required',],
                'fee_08'            => ['required',],
                'fee_09'            => ['required',],
                'fee_10'            => ['required',],
                'fee_11'            => ['required',],
                'fee_12'            => ['required',],

        ];

        $messages = [
                'custm_id.required'            => '会社名は入力必須項目です。',
                'custm_id.unique'              => 'その会社名は既に登録されています。',
                'advisor_fee.required'         => '顧問料は入力必須項目です。',
                'fee_01.required'              => '01月顧問料は入力必須項目です。',
                'fee_02.required'              => '02月顧問料は入力必須項目です。',
                'fee_03.required'              => '03月顧問料は入力必須項目です。',
                'fee_04.required'              => '04月顧問料は入力必須項目です。',
                'fee_05.required'              => '05月顧問料は入力必須項目です。',
                'fee_06.required'              => '06月顧問料は入力必須項目です。',
                'fee_07.required'              => '07月顧問料は入力必須項目です。',
                'fee_08.required'              => '08月顧問料は入力必須項目です。',
                'fee_09.required'              => '09月顧問料は入力必須項目です。',
                'fee_10.required'              => '10月顧問料は入力必須項目です。',
                'fee_11.required'              => '11月顧問料は入力必須項目です。',
                'fee_12.required'              => '12月顧問料は入力必須項目です。',
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
                'advisor_fee'       => ['required',],
                'fee_01'            => ['required',],
                'fee_02'            => ['required',],
                'fee_03'            => ['required',],
                'fee_04'            => ['required',],
                'fee_05'            => ['required',],
                'fee_06'            => ['required',],
                'fee_07'            => ['required',],
                'fee_08'            => ['required',],
                'fee_09'            => ['required',],
                'fee_10'            => ['required',],
                'fee_11'            => ['required',],
                'fee_12'            => ['required',],

        ];

        $messages = [
                'custm_id.required'            => '会社名は入力必須項目です。',
                'advisor_fee.required'         => '顧問料は入力必須項目です。',
                'fee_01.required'              => '01月顧問料は入力必須項目です。',
                'fee_02.required'              => '02月顧問料は入力必須項目です。',
                'fee_03.required'              => '03月顧問料は入力必須項目です。',
                'fee_04.required'              => '04月顧問料は入力必須項目です。',
                'fee_05.required'              => '05月顧問料は入力必須項目です。',
                'fee_06.required'              => '06月顧問料は入力必須項目です。',
                'fee_07.required'              => '07月顧問料は入力必須項目です。',
                'fee_08.required'              => '08月顧問料は入力必須項目です。',
                'fee_09.required'              => '09月顧問料は入力必須項目です。',
                'fee_10.required'              => '10月顧問料は入力必須項目です。',
                'fee_11.required'              => '11月顧問料は入力必須項目です。',
                'fee_12.required'              => '12月顧問料は入力必須項目です。',
        ];

        $validator2 = Validator::make($request->all(), $rules, $messages);

        return $validator2;
    }
}
