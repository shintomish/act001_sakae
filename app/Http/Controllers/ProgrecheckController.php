<?php
namespace App\Http\Controllers;

use File;
use Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Businesname;
use App\Models\Progrecheck;
use App\Models\Customselect;
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

class ProgrecheckController extends Controller
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
        Log::info('progrecheck index START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;
        // {{-- ALL(9999999) --}}
        $int_custom = 9999999;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->whereNull('deleted_at')
                            ->get();
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->whereNull('deleted_at')
                            ->get();
        }

        $progrechecks = Progrecheck::select(
                             'progrechecks.id               as id'
                            ,'progrechecks.organization_id  as organization_id'
                            ,'progrechecks.custm_id         as custm_id'
                            ,'progrechecks.year             as year'
                            ,'progrechecks.businm_no        as businm_no'
                            ,'progrechecks.check_01         as check_01'
                            ,'progrechecks.check_02         as check_02'
                            ,'progrechecks.check_03         as check_03'
                            ,'progrechecks.check_04         as check_04'
                            ,'progrechecks.check_05         as check_05'
                            ,'progrechecks.check_06         as check_06'
                            ,'progrechecks.check_07         as check_07'
                            ,'progrechecks.check_08         as check_08'
                            ,'progrechecks.check_09         as check_09'
                            ,'progrechecks.check_10         as check_10'
                            ,'progrechecks.check_11         as check_11'
                            ,'progrechecks.check_12         as check_12'

                            ,'customers.id                  as customers_id'
                            ,'customers.business_name       as business_name'

                            ,'businesnames.id               as businesnames_id'
                            ,'businesnames.businm_01        as businm_01'
                            ,'businesnames.businm_02        as businm_02'
                            ,'businesnames.businm_03        as businm_03'
                            ,'businesnames.businm_04        as businm_04'
                            ,'businesnames.businm_05        as businm_05'
                            ,'businesnames.businm_06        as businm_06'
                            ,'businesnames.businm_07        as businm_07'
                            ,'businesnames.businm_08        as businm_08'
                            ,'businesnames.businm_09        as businm_09'
                            ,'businesnames.businm_10        as businm_10'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('progrechecks.custm_id', '=', 'customers.id');
                            })
                            ->leftJoin('businesnames', function ($join) {
                                $join->on('progrechecks.businm_no', '=', 'businesnames.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('businesnames.deleted_at')
                            ->whereNull('progrechecks.deleted_at')
                            ->where('progrechecks.year','=',$nowyear)
                            ->sortable()
                            ->orderBy('progrechecks.id', 'asc')
                            ->paginate(300);

        $customselects = DB::table('customselects')
                        ->whereNull('deleted_at')
                        ->where('year','=',$nowyear)
                        ->orderBy('business_name', 'desc')
                        ->get();

        $common_no = '09';
        //今月の月を取得
        $nowmonth = intval($this->get_now_month());
        $keyword2  = null;
        $compacts = compact( 'userid','common_no','progrechecks', 'customers','customselects','int_custom','nowyear','nowmonth','keyword2' );
        Log::info('progrecheck index END');
        return view( 'progrecheck.index', $compacts );
        // return view('progrecheck.index')->with('compacts', $compacts);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function input(Request $request)
    {
        Log::info('progrecheck input START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;
        // {{-- ALL(9999999) --}}
        $int_custom = 9999999;
        // * 今年の年を取得
        $nowyear = $this->get_now_year();

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->whereNull('deleted_at')
                            ->get();
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->whereNull('deleted_at')
                            ->get();
        }

        $progrechecks = Progrecheck::select(
                             'progrechecks.id               as id'
                            ,'progrechecks.organization_id  as organization_id'
                            ,'progrechecks.custm_id         as custm_id'
                            ,'progrechecks.year             as year'
                            ,'progrechecks.businm_no        as businm_no'
                            ,'progrechecks.check_01         as check_01'
                            ,'progrechecks.check_02         as check_02'
                            ,'progrechecks.check_03         as check_03'
                            ,'progrechecks.check_04         as check_04'
                            ,'progrechecks.check_05         as check_05'
                            ,'progrechecks.check_06         as check_06'
                            ,'progrechecks.check_07         as check_07'
                            ,'progrechecks.check_08         as check_08'
                            ,'progrechecks.check_09         as check_09'
                            ,'progrechecks.check_10         as check_10'
                            ,'progrechecks.check_11         as check_11'
                            ,'progrechecks.check_12         as check_12'

                            ,'customers.id                  as customers_id'
                            ,'customers.business_name       as business_name'

                            ,'businesnames.id               as businesnames_id'
                            ,'businesnames.businm_01        as businm_01'
                            ,'businesnames.businm_02        as businm_02'
                            ,'businesnames.businm_03        as businm_03'
                            ,'businesnames.businm_04        as businm_04'
                            ,'businesnames.businm_05        as businm_05'
                            ,'businesnames.businm_06        as businm_06'
                            ,'businesnames.businm_07        as businm_07'
                            ,'businesnames.businm_08        as businm_08'
                            ,'businesnames.businm_09        as businm_09'
                            ,'businesnames.businm_10        as businm_10'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('progrechecks.custm_id', '=', 'customers.id');
                            })
                            ->leftJoin('businesnames', function ($join) {
                                $join->on('progrechecks.businm_no', '=', 'businesnames.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('businesnames.deleted_at')
                            ->whereNull('progrechecks.deleted_at')
                            ->where('progrechecks.year','=',$nowyear)
                            ->sortable()
                            ->orderBy('progrechecks.id', 'asc')
                            ->paginate(300);

        $customselects = DB::table('customselects')
                        ->whereNull('deleted_at')
                        ->where('year','=',$nowyear)
                        ->orderBy('business_name', 'desc')
                        ->get();

        $common_no = '09_1';
        $nowmonth = intval($this->get_now_month());    //今月の月を取得
        $compacts = compact( 'userid','common_no','progrechecks', 'customers','customselects','int_custom','nowyear','nowmonth' );

        // Log::debug('progrecheck input  progrechecks = ' . $progrechecks);
        Log::info('progrecheck input END');
        return view( 'progrecheck.input', $compacts );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('progrecheck create START');

        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;

        if($organization_id == 0) {
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->get();
        }

        // progrechecksを取得
        $progrechecks = DB::table('progrechecks')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

        $compacts = compact( 'customers','progrechecks','organization_id' );

        Log::info('progrecheck create END');
        return view( 'progrecheck.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('progrecheck store START');

        $organization = $this->auth_user_organization();

        $request->merge( ['organization_id'=> $organization->id] );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('progrecheck/create')->withErrors($validator)->withInput();
        }

// Log::debug('progrechecks store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - progrecheck store start');
        try {
            Progrecheck::create($request->all());
            DB::commit();

            Log::info('beginTransaction - progrecheck store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - progrecheck store end(rollback)');
        }

        Log::info('progrecheck store END');
        // return redirect()->route('customer.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('progrecheck.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('progrecheck show START');
        Log::info('progrecheck show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('progrecheck edit START');

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
                                ->whereNull('deleted_at')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->get();
        }

        $progrecheck = Progrecheck::find($id);

        $compacts = compact( 'progrecheck', 'customers', 'organization_id' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('progrecheck edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('progrecheck.edit', $compacts );
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
        Log::info('progrecheck update START');

        $validator = $this->get_validator($request,$id);
        if ($validator->fails()) {
            return redirect('progrecheck/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $progrecheck = Progrecheck::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - progrecheck update start');
        try {
                $progrecheck->year            = $request->year;
                $progrecheck->businm_no       = $request->businm_no;
                $progrecheck->check_01        = $request->check_01;
                $progrecheck->check_02        = $request->check_02;
                $progrecheck->check_03        = $request->check_03;
                $progrecheck->check_04        = $request->check_04;
                $progrecheck->check_05        = $request->check_05;
                $progrecheck->check_06        = $request->check_06;
                $progrecheck->check_07        = $request->check_07;
                $progrecheck->check_08        = $request->check_08;
                $progrecheck->check_09        = $request->check_09;
                $progrecheck->check_10        = $request->check_10;
                $progrecheck->check_11        = $request->check_11;
                $progrecheck->check_12        = $request->check_12;

                $progrecheck->updated_at      = now();

                $result = $progrecheck->save();

                // Log::debug('progrecheck update = ' . $progrecheck);

                DB::commit();
                Log::info('beginTransaction - progrecheck update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - progrecheck update end(rollback)');
        }

        Log::info('progrecheck update END');
        // return redirect()->route('user.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('progrecheck.input');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('progrecheck destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - progrecheck destroy start');
        //return redirect(route('customer.index'))->with('msg_danger', '許可されていない操作です');

        try {
            // customer::where('id', $id)->delete();
            $progrecheck = Progrecheck::find($id);
            $progrecheck->deleted_at     = now();
            $result = $progrecheck->save();
            DB::commit();
            Log::info('beginTransaction - progrecheck destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - progrecheck destroy end(rollback)');
        }

        Log::info('progrecheck destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('progrecheck.index');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Progrecheck $progrecheck, Request $request)
    {
        Log::info('progrecheck serch START');

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
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // progrechecksを取得
                $progrechecks = Progrecheck::select(
                                    'progrechecks.id                as id'
                                    ,'progrechecks.organization_id  as organization_id'
                                    ,'progrechecks.custm_id         as custm_id'
                                    ,'progrechecks.year             as year'
                                    ,'progrechecks.businm_no        as businm_no'
		                            ,'progrechecks.check_01         as check_01'
		                            ,'progrechecks.check_02         as check_02'
		                            ,'progrechecks.check_03         as check_03'
		                            ,'progrechecks.check_04         as check_04'
		                            ,'progrechecks.check_05         as check_05'
		                            ,'progrechecks.check_06         as check_06'
		                            ,'progrechecks.check_07         as check_07'
		                            ,'progrechecks.check_08         as check_08'
		                            ,'progrechecks.check_09         as check_09'
		                            ,'progrechecks.check_10         as check_10'
		                            ,'progrechecks.check_11         as check_11'
		                            ,'progrechecks.check_12         as check_12'

                                    ,'customers.id as customers_id'
                                    ,'customers.business_name as business_name'

                                    ,'businesnames.id               as businesnames_id'
                                    ,'businesnames.businm_01        as businm_01'
                                    ,'businesnames.businm_02        as businm_02'
                                    ,'businesnames.businm_03        as businm_03'
                                    ,'businesnames.businm_04        as businm_04'
                                    ,'businesnames.businm_05        as businm_05'
                                    ,'businesnames.businm_06        as businm_06'
                                    ,'businesnames.businm_07        as businm_07'
                                    ,'businesnames.businm_08        as businm_08'
                                    ,'businesnames.businm_09        as businm_09'
                                    ,'businesnames.businm_10        as businm_10'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('progrechecks.custm_id', '=', 'customers.id');
                                    })
                                    ->leftJoin('businesnames', function ($join) {
                                        $join->on('progrechecks.businm_no', '=', 'businesnames.id');
                                    })
                                    ->where('progrechecks.organization_id','>=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('businesnames.deleted_at')
                                    ->whereNull('progrechecks.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('progrechecks.year', '=', $keyyear)
                                    ->sortable()
                                    ->paginate(5);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // progrechecksを取得
                $progrechecks = Progrecheck::select(
                                    'progrechecks.id                as id'
                                    ,'progrechecks.organization_id  as organization_id'
                                    ,'progrechecks.custm_id         as custm_id'
                                    ,'progrechecks.year             as year'
                                    ,'progrechecks.businm_no        as businm_no'
		                            ,'progrechecks.check_01         as check_01'
		                            ,'progrechecks.check_02         as check_02'
		                            ,'progrechecks.check_03         as check_03'
		                            ,'progrechecks.check_04         as check_04'
		                            ,'progrechecks.check_05         as check_05'
		                            ,'progrechecks.check_06         as check_06'
		                            ,'progrechecks.check_07         as check_07'
		                            ,'progrechecks.check_08         as check_08'
		                            ,'progrechecks.check_09         as check_09'
		                            ,'progrechecks.check_10         as check_10'
		                            ,'progrechecks.check_11         as check_11'
		                            ,'progrechecks.check_12         as check_12'

                                    ,'customers.id                  as customers_id'
                                    ,'customers.business_name       as business_name'

                                    ,'businesnames.id               as businesnames_id'
                                    ,'businesnames.businm_01        as businm_01'
                                    ,'businesnames.businm_02        as businm_02'
                                    ,'businesnames.businm_03        as businm_03'
                                    ,'businesnames.businm_04        as businm_04'
                                    ,'businesnames.businm_05        as businm_05'
                                    ,'businesnames.businm_06        as businm_06'
                                    ,'businesnames.businm_07        as businm_07'
                                    ,'businesnames.businm_08        as businm_08'
                                    ,'businesnames.businm_09        as businm_09'
                                    ,'businesnames.businm_10        as businm_10'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('progrechecks.custm_id', '=', 'customers.id');
                                    })
                                    ->leftJoin('businesnames', function ($join) {
                                        $join->on('progrechecks.businm_no', '=', 'businesnames.id');
                                    })
                                    ->where('progrechecks.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('businesnames.deleted_at')
                                    ->whereNull('progrechecks.deleted_at')
                                    // ($keyword)の絞り込み
                                    ->where('customers.business_name', 'like', "%$keyword%")
                                    ->where('progrechecks.year', '=', $keyyear)
                                    ->sortable()
                                    ->paginate(5);
            }
        } else {
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // progrechecksを取得
                $progrechecks = Progrecheck::where('organization_id','>=',$organization_id)
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
                // progrechecksを取得
                $progrechecks = Progrecheck::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(3);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '09';
        // * 選択された年を取得
        $nowyear   = $keyyear;
        $keyword2  = $keyword;

        // Log::debug('progrecheckrs store $progrecheckrs = ' . print_r($progrecheckrs, true));
        $compacts = compact( 'userid','common_no','customers','progrechecks','nowyear','keyword2' );
        Log::info('progrecheckr serch END');

        // return view('progrecheck.index', ['progrechecks' => $progrechecks]);
        return view('progrecheck.index', $compacts);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(Progrecheck $progrecheck, Request $request)
    {
        Log::info('progrecheck serch_custom START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $sel_custom = $request->Input('sel_custom');
        $sel_year   = $request->Input('year');

        // * 今年の年を取得
        $nowyear    = intval($this->get_now_year());
        $int_custom = intval($sel_custom);
        $int_year   = intval($sel_year);

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

// var_dump($int_custom);
// die;
        // 会社が選択されたor年が今年でない
        if($int_custom != 9999999 || $nowyear != $int_year ) {

            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // progrechecksを取得
                $progrechecks = Progrecheck::select(
                                    'progrechecks.id                as id'
                                    ,'progrechecks.organization_id  as organization_id'
                                    ,'progrechecks.custm_id         as custm_id'
                                    ,'progrechecks.year             as year'
                                    ,'progrechecks.businm_no        as businm_no'
		                            ,'progrechecks.check_01         as check_01'
		                            ,'progrechecks.check_02         as check_02'
		                            ,'progrechecks.check_03         as check_03'
		                            ,'progrechecks.check_04         as check_04'
		                            ,'progrechecks.check_05         as check_05'
		                            ,'progrechecks.check_06         as check_06'
		                            ,'progrechecks.check_07         as check_07'
		                            ,'progrechecks.check_08         as check_08'
		                            ,'progrechecks.check_09         as check_09'
		                            ,'progrechecks.check_10         as check_10'
		                            ,'progrechecks.check_11         as check_11'
		                            ,'progrechecks.check_12         as check_12'

                                    ,'customers.id            as customers_id'
                                    ,'customers.business_name as business_name'

                                    ,'businesnames.id               as businesnames_id'
                                    ,'businesnames.businm_01        as businm_01'
                                    ,'businesnames.businm_02        as businm_02'
                                    ,'businesnames.businm_03        as businm_03'
                                    ,'businesnames.businm_04        as businm_04'
                                    ,'businesnames.businm_05        as businm_05'
                                    ,'businesnames.businm_06        as businm_06'
                                    ,'businesnames.businm_07        as businm_07'
                                    ,'businesnames.businm_08        as businm_08'
                                    ,'businesnames.businm_09        as businm_09'
                                    ,'businesnames.businm_10        as businm_10'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('progrechecks.custm_id', '=', 'customers.id');
                                    })
                                    ->leftJoin('businesnames', function ($join) {
                                        $join->on('progrechecks.businm_no', '=', 'businesnames.id');
                                    })
                                    ->where('progrechecks.organization_id','>=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('businesnames.deleted_at')
                                    ->whereNull('progrechecks.deleted_at')
                                    // ($sel_custom)の絞り込み
                                    ->where('progrechecks.custm_id', '=', $int_custom )
                                    ->where('progrechecks.year',     '=', $int_year   )
                                    ->sortable()
                                    ->paginate(300);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // progrechecksを取得
                $progrechecks = Progrecheck::select(
                                    'progrechecks.id                as id'
                                    ,'progrechecks.organization_id  as organization_id'
                                    ,'progrechecks.custm_id         as custm_id'
                                    ,'progrechecks.year             as year'
                                    ,'progrechecks.businm_no        as businm_no'
		                            ,'progrechecks.check_01         as check_01'
		                            ,'progrechecks.check_02         as check_02'
		                            ,'progrechecks.check_03         as check_03'
		                            ,'progrechecks.check_04         as check_04'
		                            ,'progrechecks.check_05         as check_05'
		                            ,'progrechecks.check_06         as check_06'
		                            ,'progrechecks.check_07         as check_07'
		                            ,'progrechecks.check_08         as check_08'
		                            ,'progrechecks.check_09         as check_09'
		                            ,'progrechecks.check_10         as check_10'
		                            ,'progrechecks.check_11         as check_11'
		                            ,'progrechecks.check_12         as check_12'

                                    ,'customers.id                  as customers_id'
                                    ,'customers.business_name       as business_name'

                                    ,'businesnames.id               as businesnames_id'
                                    ,'businesnames.businm_01        as businm_01'
                                    ,'businesnames.businm_02        as businm_02'
                                    ,'businesnames.businm_03        as businm_03'
                                    ,'businesnames.businm_04        as businm_04'
                                    ,'businesnames.businm_05        as businm_05'
                                    ,'businesnames.businm_06        as businm_06'
                                    ,'businesnames.businm_07        as businm_07'
                                    ,'businesnames.businm_08        as businm_08'
                                    ,'businesnames.businm_09        as businm_09'
                                    ,'businesnames.businm_10        as businm_10'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('progrechecks.custm_id', '=', 'customers.id');
                                    })
                                    ->leftJoin('businesnames', function ($join) {
                                        $join->on('progrechecks.businm_no', '=', 'businesnames.id');
                                    })
                                    ->where('progrechecks.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('businesnames.deleted_at')
                                    ->whereNull('progrechecks.deleted_at')
                                    // ($sel_custom)の絞り込み
                                    ->where('progrechecks.custm_id', '=', $int_custom )
                                    ->where('progrechecks.year',     '=', $int_year   )
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
                // progrechecksを取得
                $progrechecks = Progrecheck::where('organization_id','>=',$organization_id)
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
                // progrechecksを取得
                $progrechecks = Progrecheck::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(300);
            }

        };

        $customselects = DB::table('customselects')
                        ->whereNull('deleted_at')
                        ->where('year','=',$int_year)
                        ->orderBy('business_name', 'desc')
                        ->get();

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '09';
        $nowyear   = $int_year;
        // Log::debug('progrecheckrs store $progrecheckrs = ' . print_r($progrecheckrs, true));
        $compacts = compact( 'userid','common_no','customers','progrechecks','customselects','int_custom','nowyear' );
        Log::info('progrecheckr serch_custom END');

        // return view('progrecheck.index', ['progrechecks' => $progrechecks]);
        return view('progrecheck.index', $compacts);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_input(Progrecheck $progrecheck, Request $request)
    {
        Log::info('progrecheck serch_input START');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $userid = $user->id;

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $sel_custom = $request->Input('sel_custom');
        $sel_year   = $request->Input('year');

        // * 今年の年を取得
        $nowyear    = intval($this->get_now_year());
        $int_custom = intval($sel_custom);
        $int_year   = intval($sel_year);

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 会社が選択されたor年が今年でない
        if($int_custom != 9999999 || $nowyear != $int_year ) {

            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // progrechecksを取得
                $progrechecks = Progrecheck::select(
                                    'progrechecks.id                as id'
                                    ,'progrechecks.organization_id  as organization_id'
                                    ,'progrechecks.custm_id         as custm_id'
                                    ,'progrechecks.year             as year'
                                    ,'progrechecks.businm_no        as businm_no'
		                            ,'progrechecks.check_01         as check_01'
		                            ,'progrechecks.check_02         as check_02'
		                            ,'progrechecks.check_03         as check_03'
		                            ,'progrechecks.check_04         as check_04'
		                            ,'progrechecks.check_05         as check_05'
		                            ,'progrechecks.check_06         as check_06'
		                            ,'progrechecks.check_07         as check_07'
		                            ,'progrechecks.check_08         as check_08'
		                            ,'progrechecks.check_09         as check_09'
		                            ,'progrechecks.check_10         as check_10'
		                            ,'progrechecks.check_11         as check_11'
		                            ,'progrechecks.check_12         as check_12'

                                    ,'customers.id            as customers_id'
                                    ,'customers.business_name as business_name'

                                    ,'businesnames.id               as businesnames_id'
                                    ,'businesnames.businm_01        as businm_01'
                                    ,'businesnames.businm_02        as businm_02'
                                    ,'businesnames.businm_03        as businm_03'
                                    ,'businesnames.businm_04        as businm_04'
                                    ,'businesnames.businm_05        as businm_05'
                                    ,'businesnames.businm_06        as businm_06'
                                    ,'businesnames.businm_07        as businm_07'
                                    ,'businesnames.businm_08        as businm_08'
                                    ,'businesnames.businm_09        as businm_09'
                                    ,'businesnames.businm_10        as businm_10'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('progrechecks.custm_id', '=', 'customers.id');
                                    })
                                    ->leftJoin('businesnames', function ($join) {
                                        $join->on('progrechecks.businm_no', '=', 'businesnames.id');
                                    })
                                    ->where('progrechecks.organization_id','>=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('businesnames.deleted_at')
                                    ->whereNull('progrechecks.deleted_at')
                                    // ($sel_custom)の絞り込み
                                    ->where('progrechecks.custm_id', '=', $int_custom )
                                    ->where('progrechecks.year',     '=', $int_year   )
                                    ->sortable()
                                    ->paginate(300);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                    ->where('active_cancel','!=', 3)
                                    ->whereNull('deleted_at')
                                    ->get();
                // progrechecksを取得
                $progrechecks = Progrecheck::select(
                                    'progrechecks.id                as id'
                                    ,'progrechecks.organization_id  as organization_id'
                                    ,'progrechecks.custm_id         as custm_id'
                                    ,'progrechecks.year             as year'
                                    ,'progrechecks.businm_no        as businm_no'
		                            ,'progrechecks.check_01         as check_01'
		                            ,'progrechecks.check_02         as check_02'
		                            ,'progrechecks.check_03         as check_03'
		                            ,'progrechecks.check_04         as check_04'
		                            ,'progrechecks.check_05         as check_05'
		                            ,'progrechecks.check_06         as check_06'
		                            ,'progrechecks.check_07         as check_07'
		                            ,'progrechecks.check_08         as check_08'
		                            ,'progrechecks.check_09         as check_09'
		                            ,'progrechecks.check_10         as check_10'
		                            ,'progrechecks.check_11         as check_11'
		                            ,'progrechecks.check_12         as check_12'

                                    ,'customers.id                  as customers_id'
                                    ,'customers.business_name       as business_name'

                                    ,'businesnames.id               as businesnames_id'
                                    ,'businesnames.businm_01        as businm_01'
                                    ,'businesnames.businm_02        as businm_02'
                                    ,'businesnames.businm_03        as businm_03'
                                    ,'businesnames.businm_04        as businm_04'
                                    ,'businesnames.businm_05        as businm_05'
                                    ,'businesnames.businm_06        as businm_06'
                                    ,'businesnames.businm_07        as businm_07'
                                    ,'businesnames.businm_08        as businm_08'
                                    ,'businesnames.businm_09        as businm_09'
                                    ,'businesnames.businm_10        as businm_10'

                                    )
                                    ->leftJoin('customers', function ($join) {
                                        $join->on('progrechecks.custm_id', '=', 'customers.id');
                                    })
                                    ->leftJoin('businesnames', function ($join) {
                                        $join->on('progrechecks.businm_no', '=', 'businesnames.id');
                                    })
                                    ->where('progrechecks.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('businesnames.deleted_at')
                                    ->whereNull('progrechecks.deleted_at')
                                    // ($sel_custom)の絞り込み
                                    ->where('progrechecks.custm_id', '=', $int_custom )
                                    ->where('progrechecks.year',     '=', $int_year   )
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
                // progrechecksを取得
                $progrechecks = Progrecheck::where('organization_id','>=',$organization_id)
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
                // progrechecksを取得
                $progrechecks = Progrecheck::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(300);
            }

        };

        $customselects = DB::table('customselects')
                        ->whereNull('deleted_at')
                        ->where('year','=',$int_year)
                        ->orderBy('business_name', 'desc')
                        ->get();

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '09_1';
        $nowyear   = $int_year;
        //今月の月を取得
        $nowmonth = intval($this->get_now_month());
        // Log::debug('progrecheckrs store $progrecheckrs = ' . print_r($progrecheckrs, true));
        $compacts = compact( 'userid','common_no','customers','progrechecks','customselects','int_custom','nowyear','nowmonth' );
        Log::info('progrecheckr serch_input END');

        // return view('progrecheck.index', ['progrechecks' => $progrechecks]);
        return view('progrecheck.input', $compacts);
    }

    /**
     * [webapi]Progrecheckテーブルの更新
     */
    public function update_api(Request $request)
    {
        Log::info('update_api Progrecheck START');

        // Log::debug('update_api request = ' .print_r($request->all(),true));
        $id = $request->input('id');

        // $organization      = $this->auth_user_organization();
        $check_01    = $request->input('check_01');
        $check_02    = $request->input('check_02');
        $check_03    = $request->input('check_03');
        $check_04    = $request->input('check_04');
        $check_05    = $request->input('check_05');
        $check_06    = $request->input('check_06');
        $check_07    = $request->input('check_07');
        $check_08    = $request->input('check_08');
        $check_09    = $request->input('check_09');
        $check_10    = $request->input('check_10');
        $check_11    = $request->input('check_11');
        $check_12    = $request->input('check_12');
        // 変更前
        // {{-- <option value="1" {{ $progrecheck->check_01 == 1 ? 'selected' : '' }}>―</option> --}}
        // {{-- <option value="2" {{ $progrecheck->check_01 == 2 ? 'selected' : '' }}>△</option> --}}
        // {{-- <option value="3" {{ $progrecheck->check_01 == 3 ? 'selected' : '' }}>●</option> --}}
        // 変更後
        // <option value="1" {{ $progrecheck->check_01 == 1 ? 'selected' : '' }}>×</option>
        // <option value="2" {{ $progrecheck->check_01 == 2 ? 'selected' : '' }}>△</option>
        // <option value="3" {{ $progrecheck->check_01 == 3 ? 'selected' : '' }}>○</option>
        // if($check_01 == 1){
        //     $check_01 = 3;
        // } elseif($check_01 == 3) {
        //     $check_01 = 1;
        // }
        // if($check_02 == 1){
        //     $check_02 = 3;
        // } elseif($check_02 == 3) {
        //     $check_02 = 1;
        // }
        // if($check_03 == 1){
        //     $check_03 = 3;
        // } elseif($check_03 == 3) {
        //     $check_03 = 1;
        // }
        // if($check_04 == 1){
        //     $check_04 = 3;
        // } elseif($check_04 == 3) {
        //     $check_04 = 1;
        // }
        // if($check_05 == 1){
        //     $check_05 = 3;
        // } elseif($check_05 == 3) {
        //     $check_05 = 1;
        // }
        // if($check_06 == 1){
        //     $check_06 = 3;
        // } elseif($check_06 == 3) {
        //     $check_06 = 1;
        // }
        // if($check_07 == 1){
        //     $check_07 = 3;
        // } elseif($check_07 == 3) {
        //     $check_07 = 1;
        // }
        // if($check_08 == 1){
        //     $check_08 = 3;
        // } elseif($check_08 == 3) {
        //     $check_08 = 1;
        // }
        // if($check_09 == 1){
        //     $check_09 = 3;
        // } elseif($check_09 == 3) {
        //     $check_09 = 1;
        // }
        // if($check_10 == 1){
        //     $check_10 = 3;
        // } elseif($check_10 == 3) {
        //     $check_10 = 1;
        // }
        // if($check_11 == 1){
        //     $check_11 = 3;
        // } elseif($check_11 == 3) {
        //     $check_11 = 1;
        // }
        // if($check_12 == 1){
        //     $check_12 = 3;
        // } elseif($check_12 == 3) {
        //     $check_12 = 1;
        // }

        // Log::debug('organization_id   : ' . $organization->id);
        // Log::debug('id              : ' . $id);
        // Log::debug('check_01        : ' . $check_01);
        // Log::debug('check_02        : ' . $check_02);
        // Log::debug('check_03        : ' . $check_03);
        // Log::debug('check_04        : ' . $check_04);
        // Log::debug('check_05        : ' . $check_05);
        // Log::debug('check_06        : ' . $check_06);
        // Log::debug('check_07        : ' . $check_07);
        // Log::debug('check_08        : ' . $check_08);
        // Log::debug('check_09        : ' . $check_09);
        // Log::debug('check_10        : ' . $check_10);
        // Log::debug('check_11        : ' . $check_11);
        // Log::debug('check_12        : ' . $check_12);

        $counts = array();
        $update = [];
        if( $request->exists('check_01')  ) $update['check_01']  = $request->input('check_01');
        if( $request->exists('check_02')  ) $update['check_02']  = $request->input('check_02');
        if( $request->exists('check_03')  ) $update['check_03']  = $request->input('check_03');
        if( $request->exists('check_04')  ) $update['check_04']  = $request->input('check_04');
        if( $request->exists('check_05')  ) $update['check_05']  = $request->input('check_05');
        if( $request->exists('check_06')  ) $update['check_06']  = $request->input('check_06');
        if( $request->exists('check_07')  ) $update['check_07']  = $request->input('check_07');
        if( $request->exists('check_08')  ) $update['check_08']  = $request->input('check_08');
        if( $request->exists('check_09')  ) $update['check_09']  = $request->input('check_09');
        if( $request->exists('check_10')  ) $update['check_10']  = $request->input('check_10');
        if( $request->exists('check_11')  ) $update['check_11']  = $request->input('check_11');
        if( $request->exists('check_12')  ) $update['check_12']  = $request->input('check_12');

        $update['updated_at'] = date('Y-m-d H:i:s');
        // Log::debug('update_api Progrecheck update : ' . print_r($update,true));

        $status = array();
        DB::beginTransaction();
        Log::info('update_api Progrecheck beginTransaction - start');
        try{
            // 更新処理
            Progrecheck::where( 'id', $id )->update($update);

            $status = array( 'error_code' => 0, 'message'  => 'Your data has been changed!' );

            DB::commit();
            Log::info('update_api Progrecheck beginTransaction - end');
        }
        catch(Exception $e){
            Log::error('update_api Progrecheck exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('update_api Progrecheck beginTransaction - end(rollback)');
            echo "エラー：" . $e->getMessage();
            $status = array( 'error_code' => 501, 'message'  => $e->getMessage() );
        }

        Log::info('update_api Progrecheck END');
        return response()->json([ compact('status','counts') ]);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
                // 'custm_id'          => [
                //                         'required',
                //                         Rule::unique('progrechecks')->ignore($id),
                //                     ],
                'businm_no'  => ['required', ],

        ];

        $messages = [
                // 'custm_id.required'            => '会社名は入力必須項目です。',
                // 'custm_id.unique'              => 'その会社名は既に登録されています。',
                'businm_no.required'           => '業務名は入力必須項目です。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
}
