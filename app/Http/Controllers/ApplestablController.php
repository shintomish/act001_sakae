<?php
namespace App\Http\Controllers;

use File;
use Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Applestabl;
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

class ApplestablController extends Controller
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
        Log::info('applestabl index START');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得
        $nowyear = $this->get_now_year();

        if($organization_id == 0) {
            $customers = Customer::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->get();
            $applestabls = Applestabl::where('organization_id','>=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year',  '=', $nowyear )
                            ->sortable()
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->get();

            $applestabls = Applestabl::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->where('year',  '=', $nowyear )
                            ->sortable()
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        }

        $common_no = '11';

        $compacts = compact( 'common_no','applestabls', 'customers','nowyear' );
        Log::info('applestabl index END');
        return view( 'applestabl.index', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('applestabl create START');

        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        if($organization_id == 0) {
            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                                ->whereNull('deleted_at')
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->get();
        }

        // applestablsを取得
        $applestabls = DB::table('applestabls')
                            // 削除されていない
                            ->whereNull('deleted_at')
                            ->where('year',  '=', $nowyear )
                            ->get();

        $compacts = compact( 'customers','applestabls','organization_id','nowyear' );

        Log::info('applestabl create END');
        return view( 'applestabl.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('applestabl store START');

        $organization = $this->auth_user_organization();

        $request->merge( ['organization_id'=> $organization->id] );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('applestabl/create')->withErrors($validator)->withInput();
        }

// Log::debug('applestabls store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - applestabl store start');
        try {
            Applestabl::create($request->all());

            DB::commit();

            Log::info('beginTransaction - applestabl store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - applestabl store end(rollback)');
        }

        Log::info('applestabl store END');
        // return redirect()->route('customer.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('applestabl.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('applestabl show START');
        Log::info('applestabl show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('applestabl edit START');

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
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->get();
        } else {
            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->get();
        }

        $applestabl = Applestabl::find($id);

        // * 今年の年を取得
        // $nowyear = $this->get_now_year();
        $nowyear = $applestabl->year;

        $compacts = compact( 'applestabl', 'customers', 'organization_id', 'nowyear' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('applestabl edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('applestabl.edit', $compacts );
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
        Log::info('applestabl update START');

        $validator = $this->get_validator($request,$id);
        if ($validator->fails()) {
            return redirect('applestabl/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $applestabl = Applestabl::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - applestabl update start');
        try {
                $applestabl->year          = $request->year;
                $applestabl->companyname   = $request->companyname;
                $applestabl->estadetails   = $request->estadetails;
                $applestabl->delivery_at   = $request->delivery_at;
                $applestabl->mail_flg      = $request->mail_flg;
                $applestabl->updated_at    = now();

                $result = $applestabl->save();

                // Log::debug('applestabl update = ' . $applestabl);

                DB::commit();
                Log::info('beginTransaction - applestabl update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - applestabl update end(rollback)');
        }

        Log::info('applestabl update END');
        // return redirect()->route('user.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('applestabl.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('applestabl destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - applestabl destroy start');
        //return redirect(route('customer.index'))->with('msg_danger', '許可されていない操作です');

        try {
            // customer::where('id', $id)->delete();
            $applestabl = Applestabl::find($id);
            $applestabl->deleted_at     = now();
            $result = $applestabl->save();
            DB::commit();
            Log::info('beginTransaction - applestabl destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - applestabl destroy end(rollback)');
        }

        Log::info('applestabl destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('applestabl.index');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Applestabl $applestabl, Request $request)
    {
        Log::info('applestabl serch START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $sel_year   = $request->Input('year');
        $int_year   = intval($sel_year);

        // * 今年の年を取得
        $nowyear    = intval($this->get_now_year());

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 年が今年でない
        if($nowyear != $int_year ) {
            if($organization_id == 0) {
                $customers = Customer::where('organization_id','>=',$organization_id)
                                ->whereNull('deleted_at')
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->get();
                $applestabls = Applestabl::where('organization_id','>=',$organization_id)
                                ->whereNull('deleted_at')
                                ->where('year',                     '=', $int_year   )
                                ->sortable()
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
            } else {
                $customers = Customer::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->get();

                $applestabls = Applestabl::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                ->where('year',                     '=', $int_year   )
                                ->sortable()
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
            }

        } else {
            if($organization_id == 0) {
                $customers = Customer::where('organization_id','>=',$organization_id)
                                ->whereNull('deleted_at')
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->get();
                $applestabls = Applestabl::where('organization_id','>=',$organization_id)
                                ->whereNull('deleted_at')
                                ->sortable()
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
            } else {
                $customers = Customer::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                                ->where('active_cancel','!=', 3)
                                ->get();

                $applestabls = Applestabl::where('organization_id','=',$organization_id)
                                ->whereNull('deleted_at')
                                ->sortable()
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
            }

        }

        $common_no = '11';
        // * 選択年を取得
        $nowyear = $int_year;
        $compacts = compact( 'common_no','applestabls', 'customers','nowyear' );
        Log::info('applestablr serch END');
        return view( 'applestabl.index', $compacts );

    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
                'companyname'       => [
                                        'required',
                                    ],
                'estadetails'       => ['required',],

        ];

        $messages = [
                'companyname.required'         => '社名は入力必須項目です。',
                'estadetails.required'         => '申請・設立内容は入力必須項目です。',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
}
