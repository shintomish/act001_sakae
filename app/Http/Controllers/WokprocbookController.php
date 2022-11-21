<?php
namespace App\Http\Controllers;

use File;
use Validator;
use DateTime;
use App\Models\User;
use App\Models\Customer;
use App\Models\Wokprocbook;
use App\Models\Parameter;

// use Request;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class WokprocbookController extends Controller
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
        Log::info('wokprocbook index START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        // usersを取得
        $users = DB::table('users')
                            ->whereNull('deleted_at')
                            // ->where('organization_id','>=',$organization_id)
                            // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                            ->where('login_flg','!=',1)
                            ->get();

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
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

                            ,'users.id as u_id'
                            ,'users.login_flg as login_flg'
                            ,'users.name as name'
                            ,'users.user_id as user_id'
                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                            })
                            ->leftJoin('users', function ($join) {
                                $join->on('wokprocbooks.staff_no', '=', 'users.id');
                            })
                            ->whereNull('users.deleted_at')
                            ->whereNull('customers.deleted_at')
                            ->whereNull('wokprocbooks.deleted_at')
                            ->where('wokprocbooks.year','=',$nowyear)
                            ->sortable('refnumber','id','business_name','name','business_address')
                            ->orderBy('wokprocbooks.refnumber', 'asc')
                            ->paginate(300);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
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

                            ,'users.id as u_id'
                            ,'users.login_flg as login_flg'
                            ,'users.name as name'
                            ,'users.user_id as user_id'
                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                            })
                            ->leftJoin('users', function ($join) {
                                $join->on('wokprocbooks.staff_no', '=', 'users.id');
                            })
                            ->where('wokprocbooks.organization_id','=',$organization_id)
                            ->whereNull('users.deleted_at')
                            ->whereNull('customers.deleted_at')
                            ->whereNull('wokprocbooks.deleted_at')
                            ->where('wokprocbooks.year','=',$nowyear)
                            ->sortable('refnumber','id','business_name','name','business_address')
                            ->orderBy('wokprocbooks.refnumber', 'asc')
                            ->paginate(300);
        }

        $common_no = '07';
        $keyword2  = null;
        $frdate    = null;
        $todate    = null;

        $compacts = compact( 'userid','common_no','users','wokprocbooks', 'customers','nowyear','keyword2','frdate','todate' );
        Log::info('wokprocbook index END');
        return view( 'wokprocbook.index', $compacts );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function input(Request $request)
    {
        Log::info('wokprocbook input START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());
        // usersを取得
        $users = DB::table('users')
                            ->whereNull('deleted_at')
                            // ->where('organization_id','>=',$organization_id)
                            // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                            ->where('login_flg','!=',1)
                            ->get();

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
            $wokprocbooks = Wokprocbook::select(
                            'wokprocbooks.id as id'
                            ,'wokprocbooks.organization_id as organization_id'
                            ,'wokprocbooks.custm_id as custm_id'
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

                            ,'users.id as u_id'
                            ,'users.login_flg as login_flg'
                            ,'users.name as name'
                            ,'users.user_id as user_id'
                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                            })
                            ->leftJoin('users', function ($join) {
                                $join->on('wokprocbooks.staff_no', '=', 'users.id');
                            })
                            ->whereNull('users.deleted_at')
                            ->whereNull('customers.deleted_at')
                            ->whereNull('wokprocbooks.deleted_at')
                            // ($keyword)の絞り込み '%'.$keyword.'%'
                            // sortable()を追加
                            ->sortable('business_name','refnumber','id','name','business_address')
                            // ->orderBy('wokprocbooks.id', 'desc') 2022/08/26
                            ->orderBy('customers.business_name', 'asc')  // 2022/09/20
                            ->orderBy('wokprocbooks.refnumber', 'asc')
                            ->orderBy('wokprocbooks.proc_date', 'asc')
                            ->paginate(500);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // 2022/10/17
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
            $wokprocbooks = Wokprocbook::select(
                            'wokprocbooks.id as id'
                            ,'wokprocbooks.organization_id as organization_id'
                            ,'wokprocbooks.custm_id as custm_id'
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

                            ,'users.id as u_id'
                            ,'users.login_flg as login_flg'
                            ,'users.name as name'
                            ,'users.user_id as user_id'
                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                            })
                            ->leftJoin('users', function ($join) {
                                $join->on('wokprocbooks.staff_no', '=', 'users.id');
                            })
                            ->where('wokprocbooks.organization_id','=',$organization_id)
                            ->whereNull('users.deleted_at')
                            ->whereNull('customers.deleted_at')
                            ->whereNull('wokprocbooks.deleted_at')
                            // ($keyword)の絞り込み '%'.$keyword.'%'
                            ->sortable('business_name','refnumber','id','name','business_address')
                            // ->orderBy('wokprocbooks.id', 'desc') 2022/08/26
                            ->orderBy('customers.business_name', 'asc')  // 2022/09/20
                            ->orderBy('wokprocbooks.refnumber', 'asc')
                            ->orderBy('wokprocbooks.proc_date', 'asc')
                            ->paginate(500);
        }

        $common_no = '07_2';
        $keyword2  = null;
        $frdate    = null;
        $todate    = null;

        $compacts = compact( 'userid','common_no','users','wokprocbooks', 'customers','nowyear','keyword2','frdate','todate' );

        Log::info('wokprocbook input END');
        return view( 'wokprocbook.input', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('wokprocbook create START');

        //2022/09/20
        $user_id = auth::user()->id;
        $userid  = $user_id;
        $user_name = auth::user()->name;

        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            // usersを取得
            $users = User::where('organization_id','>=',$organization_id)
                                ->whereNull('deleted_at')
                                // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                ->where('login_flg','!=',1)
                                ->get();
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                // 2022/10/17
                                // ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->get();
        } else {
            // usersを取得
            $users = User::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                ->where('login_flg','!=',1)
                                ->get();
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                // 2022/10/17
                                // ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->get();
        }

        // 2022/09/20 整理番号の初期設定
        $wokprocbooks = DB::table('wokprocbooks')->get();
        $count = $wokprocbooks->count();
        $number = $nowyear . sprintf("%06d", ($count+1));
        $compacts = compact( 'userid','users','customers','wokprocbooks','organization_id','nowyear','user_id','user_name','number' );
        // Log::debug('wokprocbook create user_id  = ' . $user_id);
        // Log::debug('wokprocbook create user_name  = ' . $user_name);
        Log::info('wokprocbook create END');
        return view( 'wokprocbook.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('wokprocbook store START');

        $organization = $this->auth_user_organization();

        $request->merge( ['organization_id'=> $organization->id] );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('wokprocbook/create')->withErrors($validator)->withInput();
        }

// Log::debug('wokprocbooks store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - wokprocbook store start');
        try {
            Wokprocbook::create($request->all());
            DB::commit();

            Log::info('beginTransaction - wokprocbook store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - wokprocbook store end(rollback)');
        }

        Log::info('wokprocbook store END');
        // return redirect()->route('customer.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('wokprocbook.input');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('wokprocbook show START');
        Log::info('wokprocbook show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('wokprocbook edit START');

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

        if($organization_id == 0) {
            // usersを取得
            $users = User::where('organization_id','>=',$organization_id)
                                ->whereNull('deleted_at')
                                // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                ->where('login_flg','!=',1)
                                ->get();
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                // 2022/10/17
                                // ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->get();
        } else {
            // usersを取得
            $users = User::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                ->where('login_flg','!=',1)
                                ->get();
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                // 2022/10/17
                                // ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->get();
        }

        $wokprocbook = Wokprocbook::find($id);

        $compacts = compact( 'wokprocbook', 'users','customers', 'organization_id','nowyear' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('wokprocbook edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('wokprocbook.edit', $compacts );
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
        Log::info('wokprocbook update START');

        $validator = $this->get_validator($request,$id);
        if ($validator->fails()) {
            return redirect('wokprocbook/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $wokprocbook = Wokprocbook::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - wokprocbook update start');
        try {
                $wokprocbook->year                 = $request->year;
                $wokprocbook->refnumber            = $request->refnumber;
                $wokprocbook->custm_id             = $request->custm_id;
                $wokprocbook->busi_class           = $request->busi_class;
                $wokprocbook->contents_class       = $request->contents_class;
                $wokprocbook->facts_class          = $request->facts_class;
                $wokprocbook->proc_date            = $request->proc_date;
                $wokprocbook->attach_doc           = $request->attach_doc;
                $wokprocbook->filing_date          = $request->filing_date;
                $wokprocbook->login_flg            = $request->login_flg;
                $wokprocbook->staff_no             = $request->staff_no;
                $wokprocbook->remarks              = $request->remarks;
                $wokprocbook->updated_at           = now();

                $result = $wokprocbook->save();

                // Log::debug('wokprocbook update = ' . $wokprocbook);

                DB::commit();
                Log::info('beginTransaction - wokprocbook update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - wokprocbook update end(rollback)');
        }

        Log::info('wokprocbook update END');
        // return redirect()->route('user.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('wokprocbook.input');

    }
    /**
     * [webapi]Wokprocbookテーブルの更新
     */
    public function update_api(Request $request)
    {
        Log::info('update_api Wokprocbook START');

        // Log::debug('update_api request = ' .print_r($request->all(),true));
        $id = $request->input('id');

        // $organization      = $this->auth_user_organization();
        $refnumber      = $request->input('refnumber');
        $busi_class     = $request->input('busi_class');
        $contents_class = $request->input('contents_class');
        $facts_class    = $request->input('facts_class');
        $proc_date      = $request->input('proc_date');
        $attach_doc     = $request->input('attach_doc');
        $filing_date    = $request->input('filing_date');
        $staff_no       = $request->input('staff_no');

        // Log::debug('organization_id   : ' . $organization->id);
        // Log::debug('proc_date         : ' . $proc_date);
        // Log::debug('attach_doc        : ' . $attach_doc);

        $counts = array();
        $update = [];
        if( $request->exists('refnumber')       ) $update['refnumber']      = $request->input('refnumber');
        if( $request->exists('busi_class')      ) $update['busi_class']     = $request->input('busi_class');
        if( $request->exists('contents_class')  ) $update['contents_class'] = $request->input('contents_class');
        if( $request->exists('facts_class')     ) $update['facts_class']    = $request->input('facts_class');
        if( $request->exists('proc_date')       ) $update['proc_date']      = $request->input('proc_date');
        if( $request->exists('attach_doc')      ) $update['attach_doc']     = $request->input('attach_doc');
        if( $request->exists('filing_date')     ) $update['filing_date']    = $request->input('filing_date');
        if( $request->exists('staff_no')        ) $update['staff_no']       = $request->input('staff_no');

        $update['updated_at'] = date('Y-m-d H:i:s');
        // Log::debug('update_api update : ' . print_r($update,true));

        $status = array();
        DB::beginTransaction();
        Log::info('update_api Wokprocbook beginTransaction - start');
        try{
            // 更新処理
            Wokprocbook::where( 'id', $id )->update($update);

            $status = array( 'error_code' => 0, 'message'  => 'Your data has been changed!' );

            DB::commit();
            Log::info('update_api Wokprocbook beginTransaction - end');
        }
        catch(Exception $e){
            Log::error('update_api Wokprocbook exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('update_api Wokprocbook beginTransaction - end(rollback)');
            echo "エラー：" . $e->getMessage();
            $status = array( 'error_code' => 501, 'message'  => $e->getMessage() );
        }

        Log::info('update_api Wokprocbook END');
        return response()->json([ compact('status','counts') ]);
    }

    /**
     * List Data の取得
     */
    function getListData($users, $customers, $wokprocbooks, $frdate, $todate )
    {
        Log::info('getListData Wokprocbook START');

        $custm_list = array();
        $custm_rec  = array();
        $ret_val    = array();

        //---------------------------------------------------------------
        //- 返却データの整形
        //---------------------------------------------------------------
        foreach($wokprocbooks as $wokprocbook) {

            // 現在の配列を大本に追加
            if (0 < count($custm_rec)) {
                array_push($custm_list, $custm_rec);
            }
            // {{-- 整理番号 --}}
            $custm_rec['refnumber']        = sprintf('%s', $wokprocbook->refnumber);
            // {{-- /'業務区分 1:代理 2:相談'--}}
            if($wokprocbook->busi_class == 1) {
                $custm_rec['busi_class']   = "代理";
            } else {
                $custm_rec['busi_class']   = "相談";
            }

            // {{-- 社名/氏名 --}}
            $custmret = Customer::where('id',$wokprocbook->custm_id)->first();
            $custm_rec['custom_name']      = $custmret->business_name;

            // {{-- 住所 --}}
            $custm_rec['custom_addr']      = $custmret->business_address;

            // {{-- //内容（税目等）1～ --}}
            switch($wokprocbook->contents_class) {
                case (1): $custm_rec['contents_class']   = "一般的な税務・経営の相談";
                    break;
                case (2): $custm_rec['contents_class']   = "異動届（本店・代表者住所変更";
                    break;
                case (3): $custm_rec['contents_class']   = "異動届（本店住所変更）";
                    break;
                case (4): $custm_rec['contents_class']   = "確定申告の勉強会";
                    break;
                case (5): $custm_rec['contents_class']   = "帰化申請の為の数字を教示";
                    break;
                case (6): $custm_rec['contents_class']   = "源泉所得税（0円納付）";
                    break;
                case (7): $custm_rec['contents_class']   = "設立届・青色・給与支払・納期の特例承認申請書";
                    break;
                case (8): $custm_rec['contents_class']   = "法人設立・設置届出書（支店設置）";
                    break;
                case (9): $custm_rec['contents_class']   = "法定調書・給与支払報告書";
                    break;
                case (10): $custm_rec['contents_class']   = "役員報酬相談";
                    break;
                case (11): $custm_rec['contents_class']   = "法人税・消費税確定申告";
                    break;
                case (12): $custm_rec['contents_class']   = "法人税確定申告";
                    break;
                case (13): $custm_rec['contents_class']   = "消費税申告";
                    break;
                case (14): $custm_rec['contents_class']   = "確定申告書";
                    break;
                case (15): $custm_rec['contents_class']   = "確定申告書（訂正申告）";
                    break;
                case (16): $custm_rec['contents_class']   = "確定申告書・消費税申告書";
                    break;
                case (17): $custm_rec['contents_class']   = "給与支払・納期の特例承認申請書";
                    break;
                case (18): $custm_rec['contents_class']   = "年末調整過納額還付請求";
                    break;
                case (19): $custm_rec['contents_class']   = "会計処理";    //2022/08/26
                    break;
                case (20): $custm_rec['contents_class']   = "その他";
                    break;
                default:   $custm_rec['contents_class']   = "該当なし";
                    break;
            }

            // {{-- //顛末～ --}}
            switch($wokprocbook->facts_class) {
                case (1): $custm_rec['facts_class']    = "申告";
                    break;
                case (2): $custm_rec['facts_class']    = "相談";
                    break;
                case (3): $custm_rec['facts_class']    = "勉強会";
                    break;
                case (4): $custm_rec['facts_class']    = "確定申告書提出";
                    break;
                case (5): $custm_rec['facts_class']    = "還付請求書提出";
                    break;
                case (6): $custm_rec['facts_class']    = "届出書・報告書提出";
                    break;
                case (7): $custm_rec['facts_class']    = "届出書提出";
                    break;
                case (8): $custm_rec['facts_class']    = "数字の教示";
                    break;
                case (9): $custm_rec['facts_class'] = "会計処理";    //2022/08/26
                    break;
                case (10): $custm_rec['facts_class']   = "その他";
                    break;
                default:   $custm_rec['facts_class']   = "該当なし";
                    break;
            }

            // {{-- //処理年月日 --}}
            $str = "";
            if (isset($wokprocbook->proc_date)) {
                $str = ( new DateTime($wokprocbook->proc_date))->format('Y-m-d');
            }
            $custm_rec['proc_date']        = $str;

            // {{-- //添付書面 1:無 2:有 --}}
            if($wokprocbook->attach_doc) {
                $custm_rec['attach_doc']   = "無";
            } else {
                $custm_rec['attach_doc']   = "有";
            }
            // {{-- 提出日 --}}
            $str = "";
            if (isset($wokprocbook->filing_date)) {
                $str = ( new DateTime($wokprocbook->filing_date))->format('Y-m-d');
            }
            $custm_rec['filing_date']      = $str;

            // {{-- 所属 --}}
            $uesrret = User::where('id',$wokprocbook->staff_no)->first();
            if($uesrret->login_flg == 2) {
                $custm_rec['staff_no']     = "社員";
            } else {
                $custm_rec['staff_no']     = "所属";
            }
            // {{-- 担当税理士 --}}
            $custm_rec['staff_name']       = $uesrret->name;
        }

        array_push($custm_list, $custm_rec);

        // Footer
        if (0 < count($custm_rec)) {
            // {{-- //開始年月日 --}}
            $str = "指定無し";
            if (isset($frdate)) {
                $str = ( new DateTime($frdate))->format('Y-m-d');
            }
            $custm_rec['refnumber']     = "処理年月日(開始)";
            $custm_rec['busi_class']     = $str;

            // {{-- //終了年月日 --}}
            $str = "指定無し";
            if (isset($todate)) {
                $str = ( new DateTime($todate))->format('Y-m-d');
            }
            $custm_rec['custom_name']     = "処理年月日(終了)";
            $custm_rec['custom_addr']     = $str;

            $custm_rec['contents_class']   = "";
            $custm_rec['facts_class']      = "";
            $custm_rec['proc_date']        = "";
            $custm_rec['attach_doc']       = "";
            $custm_rec['filing_date']      = "";
            $custm_rec['staff_no']         = "";
            $custm_rec['staff_name']       = "";

            array_push($custm_list, $custm_rec);
        }

        $ret_val['custm_list']    = $custm_list;

        Log::info('getListData Wokprocbook END');
        return $ret_val;
    }
    /**
     * []Wokprocbook csv出力
     */
    public function export(Request $request)
    {
        Log::info('export Wokprocbook START');

        $now = Carbon::now();
        $str = $now->format('Ymd_Hi');
        $filename = '税理士業務処理簿_'.$str.'.csv';

        $organization    = $this->auth_user_organization();
        $organization_id = $organization->id;

        $frdate      = $request->input('frdate');
        $todate      = $request->input('todate');

        // 開始/終了が入力された
        if(isset($frdate) && isset($todate)) {
            // $stadate   = ( new DateTime($frdate))->format($format);
            // $enddate   = ( new DateTime($todate))->format($format);
            $stadate    = Carbon::parse($frdate)->startOfDay();
            $enddate    = Carbon::parse($todate)->endOfDay();
        } else {
            if(isset($frdate)) {
                $stadate   = Carbon::parse($frdate)->startOfDay();
                $enddate   = Carbon::parse('2050-12-31')->endOfDay();
            } else {
                $stadate   = Carbon::parse('2000-01-01')->startOfDay();
                $enddate   = Carbon::parse($todate)->endOfDay();
            }
        }

        if($organization_id == 0) {
                    // usersを取得
                    $users = User::where('organization_id','>=',$organization_id)
                                        ->whereNull('deleted_at')
                                        // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                        ->where('login_flg','!=',1)
                                        ->get();
                    // customersを取得
                    $customers = Customer::where('organization_id','>=',$organization_id)
                                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                        // 2022/10/17
                                        // ->where('active_cancel','!=', 3)
                                        ->whereNull('deleted_at')
                                        ->get();

                    // wokprocbooksを取得
                    $wokprocbooks = Wokprocbook::select(
                                'wokprocbooks.id as id'
                                ,'wokprocbooks.organization_id as organization_id'
                                ,'wokprocbooks.custm_id as custm_id'
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

                                ,'users.id as u_id'
                                ,'users.login_flg as login_flg'
                                ,'users.name as name'
                                ,'users.user_id as user_id'
                                )
                                ->leftJoin('customers', function ($join) {
                                    $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                                })
                                ->leftJoin('users', function ($join) {
                                    $join->on('wokprocbooks.staff_no', '=', 'users.id');
                                })
                                ->where('wokprocbooks.organization_id','>=',$organization_id)
                                ->whereNull('users.deleted_at')
                                ->whereNull('customers.deleted_at')
                                ->whereNull('wokprocbooks.deleted_at')
                                //proc_dateが20xx/xx/xx ~ 20xx/xx/xxのデータを取得
                                ->whereBetween("proc_date", [$stadate, $enddate])
                                ->orderByRaw('refnumber asc')
                                ->orderByRaw('proc_date desc')
                                ->get();
        } else {
            // usersを取得
            $users = User::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                ->where('login_flg','!=',1)
                                ->get();
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                // 2022/10/17
                                // ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->get();

            // wokprocbooksを取得
            $wokprocbooks = Wokprocbook::select(
                                'wokprocbooks.id as id'
                                ,'wokprocbooks.organization_id as organization_id'
                                ,'wokprocbooks.custm_id as custm_id'
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

                                ,'users.id as u_id'
                                ,'users.login_flg as login_flg'
                                ,'users.name as name'
                                ,'users.user_id as user_id'
                                )
                                ->leftJoin('customers', function ($join) {
                                    $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                                })
                                ->leftJoin('users', function ($join) {
                                    $join->on('wokprocbooks.staff_no', '=', 'users.id');
                                })
                                ->where('wokprocbooks.organization_id','=',$organization_id)
                                ->whereNull('users.deleted_at')
                                ->whereNull('customers.deleted_at')
                                ->whereNull('wokprocbooks.deleted_at')
                                //proc_dateが20xx/xx/xx ~ 20xx/xx/xxのデータを取得
                                ->whereBetween("proc_date", [$stadate, $enddate])
                                ->orderByRaw('refnumber asc')
                                ->orderByRaw('proc_date desc')
                                ->get();
        }

        //-------------------------------------------------
        //- DataCheck 2021/12/23
        //-------------------------------------------------
        if( $wokprocbooks->count() <= 0 ) {
            // Log::debug('wokprocbooks count  = ' . $wokprocbooks->count());
            session()->flash('toastr', config('toastr.csv_warning'));
            return redirect()->route('wokprocbook.input');
            // return redirect()->route('wokprocbook.input')->with('message', '対象データがありません。');
        }
        //-------------------------------------------------
        //- CSV生成
        //-------------------------------------------------
        $response = new StreamedResponse (function() use ( $users, $customers, $wokprocbooks, $frdate, $todate ){

            //-------------------------------------------------
            //- CSVにするデータ収集
            //-------------------------------------------------
            $ret_val = $this->getListData( $users, $customers, $wokprocbooks, $frdate, $todate );

            // Log::debug('wokprocbookrs getListData $ret_val = ' . print_r($ret_val, true));

            $custm_list    = $ret_val['custm_list'];

            $stream = fopen('php://output', 'w');

            // 文字化け回避
            stream_filter_prepend($stream,'convert.iconv.utf-8/cp932//TRANSLIT');

            // タイトルを追加
            fputcsv($stream,
                            [
                                 '整理番号'                 //text
                                ,'業務区分'                 //業務区分 1:代理 2:相談
                                ,'社名'                     //for
                                ,'住所'                     //for
                                ,'内容'                     //for
                                ,'顛末'                     //for
                                ,'処理年月日'               //date
                                ,'添付書面'                 //添付書面 1:無 2:有
                                ,'税務代理権限書提出日'      //date
                                ,'所属'                     //for
                                ,'担当税理士'               //for
                            ]
                        );

            foreach($custm_list as $custm_){

                $rec = array();
                array_push($rec, $custm_['refnumber'] );        // 整理番号
                array_push($rec, $custm_['busi_class'] );       // 業務区分
                array_push($rec, $custm_['custom_name'] );      // 社名
                array_push($rec, $custm_['custom_addr'] );      // 住所
                array_push($rec, $custm_['contents_class'] );   // 内容
                array_push($rec, $custm_['facts_class'] );      // 顛末
                array_push($rec, $custm_['proc_date'] );        // 処理年月日
                array_push($rec, $custm_['attach_doc'] );       // 添付書面
                array_push($rec, $custm_['filing_date'] );      // 提出日
                array_push($rec, $custm_['staff_no'] );         // 所属
                array_push($rec, $custm_['staff_name'] );       // 担当税理士

                fputcsv($stream, $rec);
            }

            fclose($stream);
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        Log::info('export Wokprocbook END');
        // return redirect()->route('wokprocbook.input')->with('message', 'CSV出力が完了しました。');
        session()->flash('toastr', config('toastr.csv_success'));
        return $response;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('wokprocbook destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - wokprocbook destroy start');
        //return redirect(route('customer.index'))->with('msg_danger', '許可されていない操作です');

        try {
            // customer::where('id', $id)->delete();
            $wokprocbook = Wokprocbook::find($id);
            $wokprocbook->deleted_at     = now();
            $result = $wokprocbook->save();
            DB::commit();
            Log::info('beginTransaction - wokprocbook destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - wokprocbook destroy end(rollback)');
        }

        Log::info('wokprocbook destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('wokprocbook.input');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Wokprocbook $wokprocbook, Request $request)
    {
        Log::info('wokprocbook serch START');

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

        // 日付が入力された
        if($keyword) {
            if($organization_id == 0) {
                // usersを取得
                $users = User::where('organization_id','>=',$organization_id)
                                    ->whereNull('deleted_at')
                                    // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                    ->where('login_flg','!=',1)
                                    ->get();
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // wokprocbooksを取得
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

                                    ,'users.id as u_id'
                                    ,'users.login_flg as login_flg'
                                    ,'users.name as name'
                                    ,'users.user_id as user_id'
                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                                    })
                                    ->leftJoin('users', function ($join) {
                                        $join->on('wokprocbooks.staff_no', '=', 'users.id');
                                    })
                                    ->where('wokprocbooks.organization_id','>=',$organization_id)
                                    ->whereNull('users.deleted_at')
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('wokprocbooks.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('wokprocbooks.year', '=', $keyyear)
                                    ->sortable('id','business_name','name','business_address')

                                    ->orderBy('customers.business_name', 'asc')  // 2022/10/17
                                    ->orderBy('wokprocbooks.refnumber', 'asc')
                                    ->orderBy('wokprocbooks.proc_date', 'asc')
                                    ->paginate(10);
            } else {
                // usersを取得
                $users = User::where('organization_id','=',$organization_id)
                                    ->whereNull('deleted_at')
                                    // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                    ->where('login_flg','!=',1)
                                    ->get();
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // wokprocbooksを取得
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

                                        ,'users.id as u_id'
                                        ,'users.login_flg as login_flg'
                                        ,'users.name as name'
                                        ,'users.user_id as user_id'
                                        )
                                        ->leftJoin('customers', function ($join) {
                                            $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                                        })
                                        ->leftJoin('users', function ($join) {
                                            $join->on('wokprocbooks.staff_no', '=', 'users.id');
                                        })
                                        ->where('wokprocbooks.organization_id','=',$organization_id)
                                        ->whereNull('users.deleted_at')
                                        ->whereNull('customers.deleted_at')
                                        ->whereNull('wokprocbooks.deleted_at')
                                        // ($keyword)の絞り込み
                                        ->where('customers.business_name', 'like', "%$keyword%")
                                        ->where('wokprocbooks.year', '=', $keyyear)
                                        ->sortable('id','business_name','name','business_address')
                                                                            
                                        ->orderBy('customers.business_name', 'asc')  // 2022/10/17
                                        ->orderBy('wokprocbooks.refnumber', 'asc')
                                        ->orderBy('wokprocbooks.proc_date', 'asc')
                                        ->paginate(10);
            }
        } else {
            if($organization_id == 0) {
                // usersを取得
                $users = User::where('organization_id','>=',$organization_id)
                                    ->whereNull('deleted_at')
                                    // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                    ->where('login_flg','!=',1)
                                    ->get();
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // wokprocbooksを取得
                $wokprocbooks = Wokprocbook::where('organization_id','>=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->orderBy('refnumber', 'asc')   //2022/10/17
                                    ->orderBy('proc_date', 'asc')
                                    ->paginate(300);
            } else {
                // usersを取得
                $users = User::where('organization_id','>=',$organization_id)
                                    ->whereNull('deleted_at')
                                    // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                    ->where('login_flg','!=',1)
                                    ->get();
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // wokprocbooksを取得
                $wokprocbooks = Wokprocbook::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->orderBy('refnumber', 'asc')   //2022/10/17
                                    ->orderBy('proc_date', 'asc')
                                    ->paginate(300);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        $common_no = '07';
        $keyword2  = $keyword;
        $frdate    = null;
        $todate    = null;
        // * 今年の年を取得
        $nowyear = $this->get_now_year();
        // Log::debug('wokprocbookrs store $wokprocbookrs = ' . print_r($wokprocbookrs, true));
        $compacts = compact( 'userid','common_no','users','customers','wokprocbooks','nowyear','keyword2','frdate','todate' );
        Log::info('wokprocbookr serch END');

        // return view('wokprocbook.index', ['wokprocbooks' => $wokprocbooks]);
        return view('wokprocbook.input', $compacts);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(Wokprocbook $wokprocbook, Request $request)
    {
        Log::info('wokprocbook serch_custom START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 日付が入力された
        if($keyword) {
            if($organization_id == 0) {
                // usersを取得
                $users = User::where('organization_id','>=',$organization_id)
                                    ->whereNull('deleted_at')
                                    // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                    ->where('login_flg','!=',1)
                                    ->get();
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // wokprocbooksを取得
                $wokprocbooks = Wokprocbook::select(
                                    'wokprocbooks.id as id'
                                    ,'wokprocbooks.organization_id as organization_id'
                                    ,'wokprocbooks.custm_id as custm_id'
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

                                    ,'users.id as u_id'
                                    ,'users.login_flg as login_flg'
                                    ,'users.name as name'
                                    ,'users.user_id as user_id'
                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                                    })
                                    ->leftJoin('users', function ($join) {
                                        $join->on('wokprocbooks.staff_no', '=', 'users.id');
                                    })
                                    ->where('wokprocbooks.organization_id','>=',$organization_id)
                                    ->whereNull('users.deleted_at')
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('wokprocbooks.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->sortable('id','business_name','name','business_address')

                                    ->orderBy('customers.business_name', 'asc')  // 2022/10/17
                                    ->orderBy('wokprocbooks.refnumber', 'asc')
                                    ->orderBy('wokprocbooks.proc_date', 'asc')
                                    ->paginate(300);
            } else {
                // usersを取得
                $users = User::where('organization_id','=',$organization_id)
                                    ->whereNull('deleted_at')
                                    // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                    ->where('login_flg','!=',1)
                                    ->get();
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // wokprocbooksを取得
                $wokprocbooks = Wokprocbook::select(
                                        'wokprocbooks.id as id'
                                        ,'wokprocbooks.organization_id as organization_id'
                                        ,'wokprocbooks.custm_id as custm_id'
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

                                        ,'users.id as u_id'
                                        ,'users.login_flg as login_flg'
                                        ,'users.name as name'
                                        ,'users.user_id as user_id'
                                        )
                                        ->leftJoin('customers', function ($join) {
                                            $join->on('wokprocbooks.custm_id', '=', 'customers.id');
                                        })
                                        ->leftJoin('users', function ($join) {
                                            $join->on('wokprocbooks.staff_no', '=', 'users.id');
                                        })
                                        ->where('wokprocbooks.organization_id','=',$organization_id)
                                        ->whereNull('users.deleted_at')
                                        ->whereNull('customers.deleted_at')
                                        ->whereNull('wokprocbooks.deleted_at')
                                        // ($keyword)の絞り込み
                                        ->where('customers.business_name', 'like', "%$keyword%")
                                        ->sortable('id','business_name','name','business_address')

                                        ->orderBy('customers.business_name', 'asc')  // 2022/10/17
                                        ->orderBy('wokprocbooks.refnumber', 'asc')
                                        ->orderBy('wokprocbooks.proc_date', 'asc')
                                        ->paginate(300);
            }
        } else {
            if($organization_id == 0) {
                // usersを取得
                $users = User::where('organization_id','>=',$organization_id)
                                    ->whereNull('deleted_at')
                                    // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                    ->where('login_flg','!=',1)
                                    ->get();
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // wokprocbooksを取得
                $wokprocbooks = Wokprocbook::where('organization_id','>=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->orderBy('refnumber', 'asc')   //2022/10/17
                                    ->orderBy('proc_date', 'asc')
                                    ->paginate(300);
            } else {
                // usersを取得
                $users = User::where('organization_id','>=',$organization_id)
                                    ->whereNull('deleted_at')
                                    // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
                                    ->where('login_flg','!=',1)
                                    ->get();
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    // 2022/10/17
                                    // ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // wokprocbooksを取得
                $wokprocbooks = Wokprocbook::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->orderBy('refnumber', 'asc')   //2022/10/17
                                    ->orderBy('proc_date', 'asc')
                                    ->paginate(300);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        $common_no = '07_2';
        $keyword2  = $keyword;
        $frdate    = null;
        $todate    = null;
        // * 今年の年を取得
        $nowyear = $this->get_now_year();
        // Log::debug('wokprocbookrs store $wokprocbookrs = ' . print_r($wokprocbookrs, true));
        $compacts = compact( 'userid','common_no','users','customers','wokprocbooks','nowyear','keyword2','frdate','todate' );
        Log::info('wokprocbookr serch_custom END');

        // return view('wokprocbook.index', ['wokprocbooks' => $wokprocbooks]);
        return view('wokprocbook.input', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
            'refnumber'     => [
                                    'required',
                                ],
            'busi_class'  => [
                                    'min:1',        //業務区分 指定された値以上か
                                    'integer',
                                    'required',
                                ],

        ];

        $messages = [
            'refnumber.required'            => '整理番号は入力必須項目です。',
            'busi_class.min'                => '業務区分を選択してください。',
            'busi_class.required'           => '業務区分は入力必須項目です。',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
}
