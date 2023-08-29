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

class ScheduleController extends Controller
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
        Log::info('schedule index START');

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
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            // 削除されていない
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
                                ->paginate(300);

        $customselects = DB::table('customselects')
                        ->whereNull('deleted_at')
                        ->where('year','=',$nowyear)
                        ->orderBy('business_name', 'desc')
                        ->get();

        $common_no = '10';
        //今月の月を取得
        $nowmonth = intval($this->get_now_month());
        $keyword2  = null;
        $compacts = compact( 'userid','common_no','progrechecks', 'customers','customselects','int_custom','nowyear','nowmonth','keyword2' );
        Log::info('schedule index END');
        return view( 'schedule.index', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('schedule create START');

        // スケジュールの新規無

        Log::info('schedule create END');
        // return view( 'schedule.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('schedule store START');


        Log::info('schedule store END');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('schedule show START');
        Log::info('schedule show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('schedule edit START');

        Log::info('schedule edit END');

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
        Log::info('schedule update START');

        Log::info('schedule update END');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('schedule destroy START');

        Log::info('schedule destroy  END');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Progrecheck $progrecheck, Request $request)
    {
        Log::info('schedule serch START');

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
        $customselects = DB::table('customselects')
                        ->whereNull('deleted_at')
                        ->where('year','=',$keyyear)
                        ->orderBy('business_name', 'desc')
                        ->get();

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '10';
        // * 選択された年を取得
        $nowyear   = $keyyear;
        $keyword2  = $keyword;
        $nowmonth = intval($this->get_now_month());    //今月の月を取得
        // Log::debug('schedulers store $schedulers = ' . print_r($schedulers, true));
        $compacts = compact( 'common_no','customers','progrechecks','nowmonth','nowyear','keyword2','customselects' );
        Log::info('scheduler serch END');

        // return view('schedule.index', ['schedules' => $schedules]);
        return view('schedule.index', $compacts);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(Progrecheck $progrecheck, Request $request)
    {
        Log::info('schedule serch_custom START');

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
                                    ->where('progrechecks.organization_id','=',$organization_id)
                                    ->whereNull('customers.deleted_at')
                                    ->whereNull('businesnames.deleted_at')
                                    ->whereNull('progrechecks.deleted_at')
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
        $common_no = '10';
        $nowyear   = $int_year;
        //今月の月を取得
        $nowmonth = intval($this->get_now_month());

        // Log::debug('schedulers store $schedulers = ' . print_r($schedulers, true));
        $compacts = compact( 'userid','common_no','customers','progrechecks','customselects','int_custom','int_year','nowmonth','nowyear'  );
        Log::info('scheduler serch_custom END');

        // return view('schedule.index', ['schedules' => $schedules]);
        return view('schedule.index', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
                // 'custm_id'          => [
                //                         'required',
                //                         Rule::unique('schedules')->ignore($id),
                //                     ],

        ];

        $messages = [
                // 'custm_id.required'            => '会社名は入力必須項目です。',
                // 'custm_id.unique'              => 'その会社名は既に登録されています。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
}
