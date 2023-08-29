<?php
namespace App\Http\Controllers;

use File;
use Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Businesname;
use App\Models\Progrecheck;
use App\Models\Schedule;
use App\Models\CustomSelect;
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

class BusinesnameController extends Controller
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
        Log::info('businesname index START');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 -----Jsonより取得  2021/12/23 -------
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();
            $businesnames = Businesname::select(
                            'businesnames.id                as id'
                            ,'businesnames.organization_id  as organization_id'
                            ,'businesnames.custm_id         as custm_id'
                            ,'businesnames.year             as year'
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
                            ,'businesnames.businm_11        as businm_11'
                            ,'businesnames.businm_12        as businm_12'
                            ,'businesnames.businm_13        as businm_13'
                            ,'businesnames.businm_14        as businm_14'
                            ,'businesnames.businm_15        as businm_15'
                            ,'businesnames.businm_16        as businm_16'
                            ,'businesnames.businm_17        as businm_17'
                            ,'businesnames.businm_18        as businm_18'
                            ,'businesnames.businm_19        as businm_19'
                            ,'businesnames.businm_20        as businm_20'

                            ,'customers.id                  as customers_id'
                            ,'customers.business_name       as business_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('businesnames.custm_id', '=', 'customers.id');
                            })
                            ->whereNull('customers.deleted_at')
                            ->whereNull('businesnames.deleted_at')
                            ->where('businesnames.year','=',$nowyear)
                            ->sortable()
                            ->orderBy('businesnames.id', 'desc')
                            ->paginate(300);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

            $businesnames = Businesname::select(
                            'businesnames.id                as id'
                            ,'businesnames.organization_id  as organization_id'
                            ,'businesnames.custm_id         as custm_id'
                            ,'businesnames.year             as year'
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
                            ,'businesnames.businm_11        as businm_11'
                            ,'businesnames.businm_12        as businm_12'
                            ,'businesnames.businm_13        as businm_13'
                            ,'businesnames.businm_14        as businm_14'
                            ,'businesnames.businm_15        as businm_15'
                            ,'businesnames.businm_16        as businm_16'
                            ,'businesnames.businm_17        as businm_17'
                            ,'businesnames.businm_18        as businm_18'
                            ,'businesnames.businm_19        as businm_19'
                            ,'businesnames.businm_20        as businm_20'

                            ,'customers.id                  as customers_id'
                            ,'customers.business_name       as business_name'

                            )
                            ->leftJoin('customers', function ($join) {
                                $join->on('businesnames.custm_id', '=', 'customers.id');
                            })
                            ->where('businesnames.organization_id','=',$organization_id)
                            ->whereNull('customers.deleted_at')
                            ->whereNull('businesnames.deleted_at')
                            ->where('businesnames.year','=',$nowyear)
                            ->sortable()
                            ->paginate(300);
        }
        $common_no = '08';

        $keyword2  = null;

        $compacts = compact( 'common_no','businesnames', 'customers','nowyear','keyword2' );
        Log::info('businesname index END');
        return view( 'businesname.index', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('businesname create START');

        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 -----Jsonより取得  2021/12/23 -------
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->orderBy('business_name', 'asc')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->orderBy('business_name', 'asc')
                                ->get();
        }

        // businesnamesを取得
        $businesnames = DB::table('businesnames')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->get();

        $keyword2  = null;
        $cus_id = 11;   //MusicBank

        $compacts = compact( 'customers','businesnames','organization_id','nowyear','keyword2','cus_id' );

        Log::info('businesname create END');
        return view( 'businesname.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('businesname store START');

        if ($request->has('submit_new')) {
            return redirect()->route('businesname.create');
        }

        $organization = $this->auth_user_organization();

        // $request->merge( ['organization_id'=> $organization->id] );
        $request->merge(
            ['organization_id' => $organization->id],
            ['user_id'         => $request->user_id],
            ['customer_id'     => $request->customer_id],
        );

        $keyyear = $request->Input('year');
        // * 選択された年を取得
        $nowyear   = $keyyear;

        // 2022/09/10
        $check = $this->get_nullcheck($request);
        if($check > 1) {
            return redirect('businesname/create')
                    ->with('error', '業務名の途中に、空のデータがあります')
                    ->withInput();
        }


        $validator = $this->get_validator($request,$nowyear,$request->customer_id);
        if ($validator->fails()) {
            return redirect('businesname/create')->withErrors($validator)->withInput();
        }

        // ALL用
        $all_no = 9999999;

        DB::beginTransaction();
        Log::info('beginTransaction - businesname store start');
        try {
            Businesname::create($request->all());

            if (isset($request->businm_01)) {

                $flg = 0;
                for($icnt = 1; $icnt < 11; $icnt++) {
                    $progrecheck = new Progrecheck();
                    $progrecheck->organization_id = $request->organization_id;
                    $progrecheck->year            = $nowyear;
                    $progrecheck->custm_id        = $request->custm_id;

                    // $schedule = new Schedule();
                    // $schedule->organization_id = $request->organization_id;
                    // $schedule->year            = $request->year;
                    // $schedule->custm_id        = $request->custm_id;

                    switch ($icnt){
                        case '1':
                                $progrecheck->businm_no      = $request->businm_01;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no         = $request->businm_01;
                                // $schedule->save();                  //  Inserts

                            break;
                        case '2':
                            if (isset($request->businm_02)) {
                                $progrecheck->businm_no       = $request->businm_02;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no          = $request->businm_02;
                                // $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        case '3':
                            if (isset($request->businm_03)) {
                                $progrecheck->businm_no       = $request->businm_03;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no          = $request->businm_03;
                                // $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        case '4':
                            if (isset($request->businm_04)) {
                                $progrecheck->businm_no       = $request->businm_04;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no          = $request->businm_04;
                                // $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        case '5':
                            if (isset($request->businm_05)) {
                                $progrecheck->businm_no       = $request->businm_05;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no          = $request->businm_05;
                                // $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        case '6':
                            if (isset($request->businm_06)) {
                                $progrecheck->businm_no       = $request->businm_06;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no          = $request->businm_06;
                                // $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        case '7':
                            if (isset($request->businm_07)) {
                                $progrecheck->businm_no       = $request->businm_07;
                                $progrecheck->save();               //  Inserts
                            //     $schedule->businm_no          = $request->businm_07;
                            //     $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        case '8':
                            if (isset($request->businm_08)) {
                                $progrecheck->businm_no       = $request->businm_08;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no          = $request->businm_08;
                                // $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        case '9':
                            if (isset($request->businm_09)) {
                                $progrecheck->businm_no       = $request->businm_09;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no          = $request->businm_09;
                                // $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        case '10':
                            if (isset($request->businm_10)) {
                                $progrecheck->businm_no       = $request->businm_10;
                                $progrecheck->save();               //  Inserts
                                // $schedule->businm_no          = $request->businm_10;
                                // $schedule->save();
                            } else {
                                $flg = 1;
                            }
                            break;
                        default:
                            // 処理
                            break;
                    }

                    if($flg == 1) {
                        $count = CustomSelect::count();
                        $scostoms2 = DB::table('customselects')->where('year','=',$nowyear)->get();

                        if (isset($scostoms2->custm_id) || $count > 0 ) {
                            $scostoms = new CustomSelect();
                            $scostoms->organization_id = $request->organization_id;
                            $scostoms->year            = $request->year;
                            // customersを取得
                            $customers = Customer::where('id','=',$request->custm_id)->first();
                            $scostoms->custm_id        = $request->custm_id;
                            $scostoms->business_name   = $customers->business_name;

                            $scostoms->save();               //  Inserts
                        } else {
                            $scostoms = new CustomSelect();
                            $scostoms->organization_id = $request->organization_id;
                            $scostoms->year            = $request->year;
                            // customersを取得
                            $customers = Customer::where('id','=',$request->custm_id)->first();
                            $scostoms->custm_id        = $request->custm_id;
                            $scostoms->business_name   = $customers->business_name;
                            $scostoms->save();               //  Inserts

                            $scostoms = new CustomSelect();
                            $scostoms->organization_id = $request->organization_id;
                            $scostoms->year            = $request->year;
                            $scostoms->custm_id        = $all_no;   //All用(9999999)
                            $scostoms->business_name   = "ALL";
                            $scostoms->save();               //  Inserts
                        }

                        break;
                    }
                }
            }

            DB::commit();

            Log::info('beginTransaction - businesname store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - businesname store end(rollback)');
        }

        Log::info('businesname store END');
        // return redirect()->route('customer.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));

        // 重複クリック対策
        $request->session()->regenerateToken();
        return redirect()->route('businesname.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('businesname show START');
        Log::info('businesname show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('businesname edit START');

        $organization    = $this->auth_user_organization();
        $organization_id = $organization->id;
        $organizations   = DB::table('organizations')
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
                                ->orderBy('business_name', 'asc')
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->whereNull('deleted_at')
                                ->orderBy('business_name', 'asc')
                                ->get();
        }

        $businesname = Businesname::find($id);

        $compacts = compact( 'businesname', 'customers', 'organization_id' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('businesname edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('businesname.edit', $compacts );
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
        Log::info('businesname update START');

        // 2022/09/10
        $check = $this->get_nullcheck($request);
        if($check > 1) {
            return redirect('businesname/'.$id.'/edit')
                    ->with('error', '業務名の途中に、空のデータがあります')
                    ->withInput();
        }

        $validator = $this->get_validator2($request,$id);

        if ($validator->fails()) {
            return redirect('businesname/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $businesname = Businesname::find($id);
        //------2022/09/30
        // Nullでなければ 編集前の項目数
        if (isset($businesname->businm_01)) {
            $maecnt = 1;
        }
        if (isset($businesname->businm_02)) {
            $maecnt = 2;
        }
        if (isset($businesname->businm_03)) {
            $maecnt = 3;
        }
        if (isset($businesname->businm_04)) {
            $maecnt = 4;
        }
        if (isset($businesname->businm_05)) {
            $maecnt = 5;
        }
        if (isset($businesname->businm_06)) {
            $maecnt = 6;
        }
        if (isset($businesname->businm_07)) {
            $maecnt = 7;
        }
        if (isset($businesname->businm_08)) {
            $maecnt = 8;
        }
        if (isset($businesname->businm_09)) {
            $maecnt = 9;
        }
        if (isset($businesname->businm_10)) {
            $maecnt = 10;
        }

        DB::beginTransaction();
        Log::info('beginTransaction - businesname update start');
        try {
//------2022/09/30
            $ret_val   = array();
            $businesname->year             = $request->year;
            $businesname->businm_01        = $request->businm_01;
            array_push($ret_val, $request->businm_01);
            $businesname->businm_02        = $request->businm_02;
            array_push($ret_val, $request->businm_02);
            $businesname->businm_03        = $request->businm_03;
            array_push($ret_val, $request->businm_03);
            $businesname->businm_04        = $request->businm_04;
            array_push($ret_val, $request->businm_04);
            $businesname->businm_05        = $request->businm_05;
            array_push($ret_val, $request->businm_05);
            $businesname->businm_06        = $request->businm_06;
            array_push($ret_val, $request->businm_06);
            $businesname->businm_07        = $request->businm_07;
            array_push($ret_val, $request->businm_07);
            $businesname->businm_08        = $request->businm_08;
            array_push($ret_val, $request->businm_08);
            $businesname->businm_09        = $request->businm_09;
            array_push($ret_val, $request->businm_09);
            $businesname->businm_10        = $request->businm_10;
            array_push($ret_val, $request->businm_10);

            // 編集後の項目数
            $atocnt = 0;
            for($icnt = 0; $icnt < 10; $icnt++) {
                // Nullならbreak
                if (! isset($ret_val[$icnt])) {
                    break;
                }
                $atocnt = $atocnt + 1;
            }
            // 編集前と編集後の項目数を比較
            if($maecnt > $atocnt) {
                return redirect('businesname/'.$id.'/edit')
                        ->with('error', '業務名を少なくすることはできません')
                        ->withInput();
            }

            //重複チェツク
            $flg = 0;
            for ($i = 0; $i < 10; $i++) {
                // Nullならbreak
                if (! isset($ret_val[$i])) {
                    break;
                }
                for ($j = $i + 1; $j < 10; $j++) {
                    if ($ret_val[$i] == $ret_val[$j]) {
                        $flg = 1;
                        break;
                    }
                }
            }
            if($flg > 0) {
                return redirect('businesname/'.$id.'/edit')
                        ->with('error', '業務名が重複しています')
                        ->withInput();
            }

            //重複がなければ登録
            $businesname->updated_at       = now();
            $businesname->save();

// Log::debug('businesname update $ret_val[$icnt]= ' . $ret_val[$icnt]);
            $nowyear = $request->year;

            //編集された業務名を揃える
            $progbefor = DB::table('progrechecks')
                ->where('year','=',$nowyear)
                ->where('custm_id', '=',$request->custm_id)
                ->get();
            $cnt = 0;
            foreach($progbefor as $progbefor2) {
                // Nullならbreak
                if (! isset($ret_val[$cnt])) {
                    break;
                }
                if($progbefor2->businm_no != $ret_val[$cnt]) {
                    $id   = $progbefor2->id;
                    $prog = Progrecheck::find($id);
                    $prog->businm_no       = $ret_val[$cnt];
                    $prog->updated_at      = now();
                    $prog->save();
                }
                $cnt = $cnt + 1;
            }

            // 追加された業務名を登録
            for($icnt = 0; $icnt < 10; $icnt++) {
                // Nullならbreak
                if (! isset($ret_val[$icnt])) {
                    break;
                }
                $progcount = DB::table('progrechecks')
                    ->where('year','=',$nowyear)
                    ->where('custm_id', '=',$request->custm_id)
                    ->where('businm_no','=',$ret_val[$icnt])
                    ->get();
                $prog = DB::table('progrechecks')
                    ->where('year','=',$nowyear)
                    ->where('custm_id', '=',$request->custm_id)
                    ->where('businm_no','=',$ret_val[$icnt])
                    ->first();

                if($progcount->count() > 0 ){
                    // 未処理
                // 追加された
                } else {
                    $progrecheck = new Progrecheck();
                    $progrecheck->organization_id = $businesname->organization_id;
                    $progrecheck->year            = $nowyear;
                    $progrecheck->custm_id        = $request->custm_id;
                    $progrecheck->businm_no       = $ret_val[$icnt];
                    $progrecheck->save();
                }
            }

//------2022/09/30
                DB::commit();
                Log::info('beginTransaction - businesname update end(commit)');
            }
            catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - businesname update end(rollback)');
        }

        Log::info('businesname update END');
        // return redirect()->route('user.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));

        // 重複クリック対策
        $request->session()->regenerateToken();
        return redirect()->route('businesname.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('businesname destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - businesname destroy start');
        //return redirect(route('customer.index'))->with('msg_danger', '許可されていない操作です');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        try {
            // customer::where('id', $id)->delete();
            $businesname = Businesname::find($id);
            $businesname->deleted_at     = now();
            $result = $businesname->save();

            if($organization_id == 0) {
                // progrechecksを取得
                $progrecheck = Progrecheck::where('organization_id','>=',$organization_id)
                                            ->where('custm_id','=',$businesname->custm_id)
                                            ->where('year','=',$businesname->year)
                                            ->get();
                // customselectsを取得
                $customselect = CustomSelect::where('organization_id','>=',$organization_id)
                                            ->where('custm_id','=',$businesname->custm_id)
                                            ->where('year','=',$businesname->year)
                                            ->get();
            } else {
                // progrechecksを取得
                $progrecheck = Progrecheck::where('organization_id','=',$organization_id)
                                            ->where('custm_id','=',$businesname->custm_id)
                                            ->where('year','=',$businesname->year)
                                            ->get();
                // customselectsを取得
                $customselect = CustomSelect::where('organization_id','>=',$organization_id)
                                            ->where('custm_id','=',$businesname->custm_id)
                                            ->where('year','=',$businesname->year)
                                            ->get();
            }
            foreach($progrecheck as $progrecheck2) {
                $progrecheck2->deleted_at     = now();
                $result = $progrecheck2->save();
            }
            foreach($customselect as $customselect2) {
                $customselect2->deleted_at     = now();
                $result = $customselect2->save();
            }
            DB::commit();
            Log::info('beginTransaction - businesname destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - businesname destroy end(rollback)');
        }

        Log::info('businesname destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('businesname.index');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(Businesname $businesname, Request $request)
    {
        Log::info('businesname serch_custom START');

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
                // businesnamesを取得
                $businesnames = Businesname::select(
                        'businesnames.id                as id'
                        ,'businesnames.organization_id  as organization_id'
                        ,'businesnames.custm_id         as custm_id'
                        ,'businesnames.year             as year'
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
                        ,'businesnames.businm_11        as businm_11'
                        ,'businesnames.businm_12        as businm_12'
                        ,'businesnames.businm_13        as businm_13'
                        ,'businesnames.businm_14        as businm_14'
                        ,'businesnames.businm_15        as businm_15'
                        ,'businesnames.businm_16        as businm_16'
                        ,'businesnames.businm_17        as businm_17'
                        ,'businesnames.businm_18        as businm_18'
                        ,'businesnames.businm_19        as businm_19'
                        ,'businesnames.businm_20        as businm_20'
                        ,'customers.id                  as customers_id'
                        ,'customers.business_name       as business_name'
                    )
                    ->leftJoin('customers', function ($join) {
                        $join->on('businesnames.custm_id', '=', 'customers.id');
                    })

                    ->where('businesnames.organization_id','>=',$organization_id)
                    ->whereNull('customers.deleted_at')
                    ->whereNull('businesnames.deleted_at')
                    // ($keyword)の絞り込み
                    ->where('customers.business_name', 'like', "%$keyword%")
                    ->where('businesnames.year', '=', $keyyear)
                    ->sortable()
                    ->paginate(5);
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','=',$organization_id)
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->whereNull('deleted_at')
                    ->get();
                // businesnamesを取得
                $businesnames = Businesname::select(
                        'businesnames.id                as id'
                        ,'businesnames.organization_id  as organization_id'
                        ,'businesnames.custm_id         as custm_id'
                        ,'businesnames.year             as year'
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
                        ,'businesnames.businm_11        as businm_11'
                        ,'businesnames.businm_12        as businm_12'
                        ,'businesnames.businm_13        as businm_13'
                        ,'businesnames.businm_14        as businm_14'
                        ,'businesnames.businm_15        as businm_15'
                        ,'businesnames.businm_16        as businm_16'
                        ,'businesnames.businm_17        as businm_17'
                        ,'businesnames.businm_18        as businm_18'
                        ,'businesnames.businm_19        as businm_19'
                        ,'businesnames.businm_20        as businm_20'
                        ,'customers.id                  as customers_id'
                        ,'customers.business_name       as business_name'
                    )
                    ->leftJoin('customers', function ($join) {
                        $join->on('businesnames.custm_id', '=', 'customers.id');
                    })
                    ->where('businesnames.organization_id','=',$organization_id)
                    ->whereNull('customers.deleted_at')
                    ->whereNull('businesnames.deleted_at')
                    // ($keyword)の絞り込み
                    ->where('customers.business_name', 'like', "%$keyword%")
                    ->where('businesnames.year', '=', $keyyear)
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
                // businesnamesを取得
                $businesnames = Businesname::where('organization_id','>=',$organization_id)
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
                // businesnamesを取得
                $businesnames = Businesname::where('organization_id','=',$organization_id)
                                    // 削除されていない
                                    ->whereNull('deleted_at')
                                    // sortable()を追加
                                    ->sortable()
                                    ->paginate(3);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no = '08';
        // * 選択された年を取得
        $nowyear   = $keyyear;
        $keyword2  = $keyword;

        // Log::debug('businesname store $businesname = ' . print_r($businesnames, true));
        $compacts = compact( 'common_no','customers','businesnames','nowyear','keyword2' );
        Log::info('businesname serch_custom END');

        // return view('businesname.index', ['businesnames' => $businesnames]);
        return view('businesname.index', $compacts);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_cus_id(Businesname $businesname, Request $request)
    {
        Log::info('businesname serch_cus_id START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');

        // * 今年の年を取得2 -----Jsonより取得  2021/12/23 -------
        $keyyear   = intval($this->get_now_year2());
        // $keyyear = $request->Input('year');

        // Log::debug('businesname serch_cus_id $keyword = ' . $keyword );
        // Log::debug('businesname serch_cus_id $keyyear = ' . $keyyear );

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        $cus_id  = 0;
        $flg     = 0;
        // 入力された
        if($keyword) {
            if($organization_id == 0) {
                // customersを取得
                $customers   = DB::table('customers')
                    ->where('organization_id','>=',$organization_id)
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->whereNull('deleted_at')
                    // ($keyword)の絞り込み
                    ->where('business_name', 'like', "%$keyword%")
                    ->get();
                    if($customers->count() == 0 ) {
                        $flg     = 1;
                    } else {
                        $customers   = DB::table('customers')
                        ->where('organization_id','>=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        ->whereNull('deleted_at')
                        // ($keyword)の絞り込み
                        ->where('business_name', 'like', "%$keyword%")
                        ->first();
                        $cus_id = $customers->id;
                    }
            } else {
                // customersを取得
                $customers   = DB::table('customers')
                    ->where('organization_id','=',$organization_id)
                   // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->whereNull('deleted_at')
                   // ($keyword)の絞り込み
                    ->where('business_name', 'like', "%$keyword%")
                    ->get();
                    if($customers->count() == 0 ) {
                        $flg     = 1;
                    } else {
                        $customers   = DB::table('customers')
                        ->where('organization_id','=',$organization_id)
                        // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                        ->where('active_cancel','!=', 3)
                        ->whereNull('deleted_at')
                        // ($keyword)の絞り込み
                        ->where('business_name', 'like', "%$keyword%")
                        ->first();
                        $cus_id = $customers->id;
                    }
            }
        };

        if($cus_id <> 0){
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->whereNull('deleted_at')
                    ->where('id','=', $cus_id)
                    ->get();
                // businesnamesを取得
                $businesnames = Businesname::where('organization_id','>=',$organization_id)
                    // 削除されていない
                    ->whereNull('deleted_at')
                    // sortable()を追加
                    ->sortable()
                    ->get();
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->where('id','=', $cus_id)
                    ->whereNull('deleted_at')
                    ->get();
                // businesnamesを取得
                $businesnames = Businesname::where('organization_id','=',$organization_id)
                    // 削除されていない
                    ->whereNull('deleted_at')
                    // sortable()を追加
                    ->sortable()
                    ->get();
            }
        } else {
            if($organization_id == 0) {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->whereNull('deleted_at')
                    ->orderBy('business_name', 'asc')
                    ->get();
                // businesnamesを取得
                $businesnames = Businesname::where('organization_id','>=',$organization_id)
                    // 削除されていない
                    ->whereNull('deleted_at')
                    // sortable()を追加
                    ->sortable()
                    ->get();
            } else {
                // customersを取得
                $customers = Customer::where('organization_id','>=',$organization_id)
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->whereNull('deleted_at')
                    ->orderBy('business_name', 'asc')
                    ->get();
                // businesnamesを取得
                $businesnames = Businesname::where('organization_id','=',$organization_id)
                    // 削除されていない
                    ->whereNull('deleted_at')
                    // sortable()を追加
                    ->sortable()
                    ->get();
            }
        }

        if($flg == 1){
            // toastrというキーでメッセージを格納
            session()->flash('toastr', config('toastr.custom_warning'));
            $cus_id = 11;   //MusicBank
        }

        $common_no = '08';

        $nowyear   = $keyyear;
        $keyword2  = $keyword;

        // Log::debug('businesname serch_cus_id $cus_id = ' . print_r($cus_id, true));
        $compacts = compact( 'common_no','customers','businesnames','nowyear','keyword2','cus_id' );
        Log::info('businesname serch_cus_id END');

        return view('businesname.create', $compacts);
    }


    /**
     *
     */
    public function get_nullcheck(Request $request)
    {
        $flg = 0;
        // Nullでない
        if ( isset($request->businm_01) ) {
            if (! isset($request->businm_02)
            ) {
                $flg = 1;
            }
        }
        // Nullでない
        if ( isset($request->businm_02) ) {
            if (! isset($request->businm_01)
            ) {
                $flg = 2;
            }
        }
        // Nullでない
        if ( isset($request->businm_03) ) {
            if (! isset($request->businm_01) ||
                ! isset($request->businm_02)
            ) {
                $flg = 3;
            }
        }
        // Nullでない
        if ( isset($request->businm_04) ) {
            if (! isset($request->businm_01) ||
                ! isset($request->businm_02) ||
                ! isset($request->businm_03)
            ) {
                $flg = 4;
            }
        }
        // Nullでない
        if ( isset($request->businm_05) ) {
            if (! isset($request->businm_01) ||
                ! isset($request->businm_02) ||
                ! isset($request->businm_03) ||
                ! isset($request->businm_04)
            ) {
                $flg = 5;
            }
        }
        // Nullでない
        if ( isset($request->businm_06) ) {
            if (! isset($request->businm_01) ||
                ! isset($request->businm_02) ||
                ! isset($request->businm_03) ||
                ! isset($request->businm_04) ||
                ! isset($request->businm_05)
            ) {
                $flg = 6;
            }
        }
        // Nullでない
        if ( isset($request->businm_07) ) {
            if (! isset($request->businm_01) ||
                ! isset($request->businm_02) ||
                ! isset($request->businm_03) ||
                ! isset($request->businm_04) ||
                ! isset($request->businm_05) ||
                ! isset($request->businm_06)
            ) {
                $flg = 7;
            }
        }
        // Nullでない
        if ( isset($request->businm_08) ) {
            if (! isset($request->businm_01) ||
                ! isset($request->businm_02) ||
                ! isset($request->businm_03) ||
                ! isset($request->businm_04) ||
                ! isset($request->businm_05) ||
                ! isset($request->businm_06) ||
                ! isset($request->businm_07)
            ) {
                $flg = 8;
            }
        }
        // Nullでない
        if ( isset($request->businm_09) ) {
            if (! isset($request->businm_01) ||
                ! isset($request->businm_02) ||
                ! isset($request->businm_03) ||
                ! isset($request->businm_04) ||
                ! isset($request->businm_05) ||
                ! isset($request->businm_06) ||
                ! isset($request->businm_07) ||
                ! isset($request->businm_08)
            ) {
                $flg = 9;
            }
        }
        // Nullでない
        if ( isset($request->businm_10) ) {
            if (! isset($request->businm_01) ||
                ! isset($request->businm_02) ||
                ! isset($request->businm_03) ||
                ! isset($request->businm_04) ||
                ! isset($request->businm_05) ||
                ! isset($request->businm_06) ||
                ! isset($request->businm_07) ||
                ! isset($request->businm_08) ||
                ! isset($request->businm_09)
            ) {
                $flg = 10;
            }
        }

        // Log::debug('businesname get_nullcheck $flg = ' . print_r($flg, true));

        return $flg;
    }
    /**
     *
     */
    public function get_validator(Request $request,$nowyear,$id)
    {
        $rules   = [
                'custm_id'   => [
                                    'required',
                                    Rule::unique('businesnames')->whereNull('deleted_at')
                                    ->where('year',$nowyear)
                                    ->ignore($id),

                                ],


                'businm_01'  => ['required', ],
                // 'businm_02'  => ['required', ],
                // 'businm_03'  => ['required', ],
                // 'businm_04'  => ['required', ],
                // 'businm_05'  => ['required', ],
                // 'businm_06'  => ['required', ],
                // 'businm_07'  => ['required', ],
                // 'businm_08'  => ['required', ],
        ];

        $messages = [
                'custm_id.required'            => '会社名は入力必須項目です。',
                'custm_id.unique'              => 'その会社名は既に登録されています。',
                'businm_01.required'           => '業務名1は入力必須項目です。',
                // 'businm_02.required'           => '業務名2は入力必須項目です。',
                // 'businm_03.required'           => '業務名3は入力必須項目です。',
                // 'businm_04.required'           => '業務名4は入力必須項目です。',
                // 'businm_05.required'           => '業務名5は入力必須項目です。',
                // 'businm_06.required'           => '業務名6は入力必須項目です。',
                // 'businm_07.required'           => '業務名7は入力必須項目です。',
                // 'businm_08.required'           => '業務名8は入力必須項目です。',
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

        ];

        $messages = [
                'custm_id.required'            => '会社名は入力必須項目です。',
        ];

        $validator2 = Validator::make($request->all(), $rules, $messages);

        return $validator2;
    }
}
