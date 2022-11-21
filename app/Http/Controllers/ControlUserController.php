<?php
namespace App\Http\Controllers;

use File;
use Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\ControlUser;
use App\Models\Parameter;
use App\Models\Wokprocbook;

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

class ControlUserController extends Controller
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
        Log::info('controluser index START');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 一覧の組織IDを組織名にするため organizationsを取得
        $organizations = DB::table('organizations')
                // 組織の絞り込み
                ->when($organization_id != 0, function ($query) use ($organization_id) {
                    return $query->where( 'id', $organization_id );
                })
                // 削除されていない
                ->whereNull('deleted_at')
                ->get();

        if($organization_id == 0) {
            $users = User::where('organization_id','>=',$organization_id)
                            ->where('login_flg','=', 1 )  //顧客
                            ->whereNull('deleted_at')
                            ->get();
            $customers = Customer::whereNull('deleted_at')
                            ->get();

            // ユーザー毎の法人の件数(1件以上)
            $user_counts = ControlUser::select(DB::raw('user_id as u_id, COUNT(user_id) AS user_id_count'))
                            ->groupBy('user_id','u_id')
                            ->having('user_id_count', '>', 1)
                            ->get();
// Log::debug('controlusers index $user_counts** = ' . $user_counts);

            $detail = [];
            $row  = 0;
            foreach($user_counts as $user_count) {
                $detail[$row] = $user_count->u_id;
                $row  = $row + 1;
            }

// Log::debug('controlusers index $detail = ' . print_r($detail, true));
            $controlusers = ControlUser::select(
                 'controlusers.id              as id'
                ,'controlusers.organization_id as organization_id'
                ,'controlusers.user_id         AS user_id'
                ,'controlusers.customer_id     as customer_id'
                ,'users.id                     as users_id'
                ,'users.name                   as users_name'
                ,'customers.id                 as customers_id'
                ,'customers.business_name      as business_name'
                )

                ->leftJoin('users', function ($join) {
                    $join->on('controlusers.user_id', '=', 'users.id');
                })
                ->leftJoin('customers', function ($join) {
                    $join->on('controlusers.customer_id', '=', 'customers.id');
                })
                ->where('controlusers.organization_id','>=',$organization_id)
                //user_idカラムの値が〇〇、もしくは✕✕の情報を取得
                ->whereIn('controlusers.user_id',$detail)
                ->whereNull('customers.deleted_at')
                ->whereNull('users.deleted_at')
                ->whereNull('controlusers.deleted_at')
                ->orderBy('controlusers.user_id', 'asc')    //2022/10/17
                ->orderBy('controlusers.customer_id', 'asc')    //2022/10/17
                ->sortable()
                ->paginate(300);
        } else {
            $users = User::where('organization_id','=',$organization_id)
                            ->where('login_flg','=', 1 )  //顧客
                            ->whereNull('deleted_at')
                            ->get();
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->get();

            // ユーザー毎の法人の件数(1件以上)
            $user_counts = ControlUser::select(DB::raw('user_id as u_id, COUNT(user_id) AS user_id_count'))
                            ->groupBy('user_id','u_id')
                            ->having('user_id_count', '>', 1)
                            ->get();
// Log::debug('controlusers index $user_counts** = ' . $user_counts);

            $detail = [];
            $row  = 0;
            foreach($user_counts as $user_count) {
                $detail[$row] = $user_count->u_id;
                $row  = $row + 1;
            }

// Log::debug('controlusers index $detail = ' . print_r($detail, true));
            $controlusers = ControlUser::select(
                 'controlusers.id              as id'
                ,'controlusers.organization_id as organization_id'
                ,'controlusers.user_id         AS user_id'
                ,'controlusers.customer_id     as customer_id'
                ,'users.id                     as users_id'
                ,'users.name                   as users_name'
                ,'customers.id                 as customers_id'
                ,'customers.business_name      as business_name'
                )

                ->leftJoin('users', function ($join) {
                    $join->on('controlusers.user_id', '=', 'users.id');
                })
                ->leftJoin('customers', function ($join) {
                    $join->on('controlusers.customer_id', '=', 'customers.id');
                })
                ->where('controlusers.organization_id','=',$organization_id)
                //user_idカラムの値が〇〇、もしくは✕✕の情報を取得
                ->whereIn('controlusers.user_id',$detail)
                ->whereNull('customers.deleted_at')
                ->whereNull('users.deleted_at')
                ->whereNull('controlusers.deleted_at')
                ->orderBy('controlusers.user_id', 'asc')    //2022/10/17
                ->orderBy('controlusers.customer_id', 'asc')    //2022/10/17
                ->sortable()
                ->paginate(300);
        }

        $common_no ='00_4';
        $keyword2  = null;
        // * 今年の年を取得
        $nowyear = $this->get_now_year();
        $compacts = compact( 'common_no','controlusers', 'organizations', 'users','customers','keyword2','nowyear' );
        Log::info('controluser index END');
        return view( 'controluser.index', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('controluser create START');

        $organization = $this->auth_user_organization();
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
            $users = User::whereNull('deleted_at')
                            ->where('login_flg','=', 1 )  //顧客
                            // 2021/12/13
                            ->orderBy('name', 'asc')
                            ->get();
            $customers = Customer::whereNull('deleted_at')
                            // 2021/12/13
                            ->orderBy('business_name', 'asc')
                            ->get();
            $controlusers = ControlUser::whereNull('deleted_at')
                            ->get();
        } else {
            $users = User::where('organization_id','=',$organization_id)
                            ->where('login_flg','=', 1 )  //顧客
                            ->whereNull('deleted_at')
                            // 2021/12/13
                            ->orderBy('name', 'asc')
                            ->get();
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            // 2021/12/13
                            ->orderBy('business_name', 'asc')
                            ->get();
            $controlusers = ControlUser::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->get();
        }

        $common_no ='00_4';
        $compacts = compact( 'common_no','organizations', 'controlusers','users','customers' );

        Log::info('controluser create END');
        return view( 'controluser.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('controluser store START');

        $organization = $this->auth_user_organization();

        $request->merge(
            ['organization_id' => $organization->id],
            ['user_id'         => $request->user_id],
            ['customer_id'     => $request->customer_id],
        );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('ctluser/create')->withErrors($validator)->withInput();
        }

// Log::debug('controluser store $request = ' . print_r($request->all(), true));

        DB::beginTransaction();
        Log::info('beginTransaction - controluser store start');
        try {
            $controluser = new ControlUser();
            $controluser->organization_id   = $request->organization_id;
            $controluser->user_id           = $request->user_id;
            $controluser->customer_id       = $request->customer_id;
            $controluser->save();           //  Inserts
            DB::commit();

            Log::info('beginTransaction - controluser store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - controluser store end(rollback)');
        }

        Log::info('controluser store END');
        // return redirect()->route('customer.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('ctluser.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('controluser show START');
        Log::info('controluser show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('controluser edit START');

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
            $users = User::whereNull('deleted_at')
                            ->where('login_flg','=', 1 )  //顧客
                            // 2021/12/13
                            ->orderBy('name', 'asc')
                            ->get();
            $customers = Customer::whereNull('deleted_at')
                            // 2021/12/13
                            ->orderBy('business_name', 'asc')
                            ->get();
        } else {
            $users = User::where('organization_id','=',$organization_id)
                            ->where('login_flg','=', 1 )  //顧客
                            ->whereNull('deleted_at')
                            // 2021/12/13
                            ->orderBy('name', 'asc')
                            ->get();
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->orderBy('business_name', 'asc')
                            ->get();
        }

        $controlusers = ControlUser::find($id);

        $compacts = compact( 'users','controlusers', 'organizations', 'customers' );

        // Log::debug('customer edit  = ' . $customer);
        Log::info('controluser edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('controluser.edit', $compacts );
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
        Log::info('controluser update START');

        $validator = $this->get_validator($request,$id);
        if ($validator->fails()) {
            return redirect('ctluser/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $organization    = $this->auth_user_organization();
        $organization_id = $organization->id;

        if($organization_id == 0) {
            $users = User::whereNull('deleted_at')
                            ->where('login_flg','=', 1 )  //顧客
                            ->get();
            $customers = Customer::whereNull('deleted_at')
                            ->get();
        } else {
            $users = User::where('organization_id','=',$organization_id)
                            ->where('login_flg','=', 1 )  //顧客
                            ->whereNull('deleted_at')
                            ->get();
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->get();
        }

        $controluser = ControlUser::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - controluser update start');
        try {
                $controluser->organization_id   = $request->organization_id;
                $controluser->user_id           = $request->user_id;
                $controluser->customer_id       = $request->customer_id;
                $result = $controluser->save();

                // Log::debug('controluser update controluser = ' . $controluser);

                DB::commit();
                Log::info('beginTransaction - controluser update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - controluser update end(rollback)');
        }

        Log::info('controluser update END');
        // return redirect()->route('user.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('ctluser.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('controluser destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - controluser destroy start');
        //return redirect(route('customer.index'))->with('msg_danger', '許可されていない操作です');

        try {
            // customer::where('id', $id)->delete();
            $controluser = ControlUser::find($id);
            $controluser->deleted_at     = now();
            $result = $controluser->save();
            DB::commit();
            Log::info('beginTransaction - controluser destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - controluser destroy end(rollback)');
        }

        Log::info('controluser destroy  END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('ctluser.index');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Request $request)
    {
        Log::info('controluser serch START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;
        // 一覧の組織IDを組織名にするため organizationsを取得
        $organizations = DB::table('organizations')
                // 組織の絞り込み
                ->when($organization_id != 0, function ($query) use ($organization_id) {
                    return $query->where( 'id', $organization_id );
                })
                // 削除されていない
                ->whereNull('deleted_at')
                ->get();

        if($organization_id == 0) {
            $users = User::whereNull('deleted_at')
                            ->where('login_flg','=', 1 )  //顧客
                            ->get();
            $customers = Customer::whereNull('deleted_at')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->get();
        } else {
            $users = User::where('organization_id','=',$organization_id)
                            ->where('login_flg','=', 1 )  //顧客
                            ->whereNull('deleted_at')
                            ->get();
            $customers = Customer::where('organization_id','=',$organization_id)
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->whereNull('deleted_at')
                            ->get();
        }

        // ユーザー毎の法人の件数(1件以上)
        $user_counts = ControlUser::select(DB::raw('user_id as u_id, COUNT(user_id) AS user_id_count'))
                        ->groupBy('user_id','u_id')
                        ->having('user_id_count', '>', 1)
                        ->get();
// Log::debug('controlusers index $user_counts** = ' . $user_counts);

        $detail = [];
        $row  = 0;
        foreach($user_counts as $user_count) {
            $detail[$row] = $user_count->u_id;
            $row  = $row + 1;
        }

        // 日付が入力された
        if($keyword) {
            if($organization_id == 0) {
                $controlusers = ControlUser::select(
                     'controlusers.id              as id'
                    ,'controlusers.organization_id as organization_id'
                    ,'controlusers.user_id         as user_id'
                    ,'controlusers.customer_id     as customer_id'
                    ,'users.id                     as users_id'
                    ,'users.name                   as users_name'
                    ,'customers.id                 as customers_id'
                    ,'customers.business_name      as business_name'
                    )
                    ->leftJoin('users', function ($join) {
                        $join->on('controlusers.user_id', '=', 'users.id');
                    })
                    ->leftJoin('customers', function ($join) {
                        $join->on('controlusers.customer_id', '=', 'customers.id');
                    })
                    ->where('controlusers.organization_id','>=',$organization_id)
                    //user_idカラムの値が〇〇、もしくは✕✕の情報を取得
                    ->whereIn('controlusers.user_id',$detail)
                    ->whereNull('customers.deleted_at')
                    ->whereNull('users.deleted_at')
                    ->whereNull('controlusers.deleted_at')
                    // ($keyword)の絞り込み
                    ->where('customers.business_name', 'like', "%$keyword%")
                    ->orderBy('controlusers.user_id', 'asc')    //2022/10/17
                    ->orderBy('controlusers.customer_id', 'asc')    //2022/10/17
                    // sortable()を追加
                    ->sortable()
                    ->paginate(300);
            } else {
                $controlusers = ControlUser::select(
                    'controlusers.id              as id'
                    ,'controlusers.organization_id as organization_id'
                    ,'controlusers.user_id         as user_id'
                    ,'controlusers.customer_id     as customer_id'
                    ,'users.id                     as users_id'
                    ,'users.name                   as users_name'
                    ,'customers.id                 as customers_id'
                    ,'customers.business_name      as business_name'
                    )
                    ->leftJoin('users', function ($join) {
                        $join->on('controlusers.user_id', '=', 'users.id');
                    })
                    ->leftJoin('customers', function ($join) {
                        $join->on('controlusers.customer_id', '=', 'customers.id');
                    })
                    ->where('controlusers.organization_id','=',$organization_id)
                    //user_idカラムの値が〇〇、もしくは✕✕の情報を取得
                    ->whereIn('controlusers.user_id',$detail)
                    ->whereNull('customers.deleted_at')
                    ->whereNull('users.deleted_at')
                    ->whereNull('controlusers.deleted_at')
                    // ($keyword)の絞り込み
                    ->where('customers.business_name', 'like', "%$keyword%")
                    ->orderBy('controlusers.user_id', 'asc')    //2022/10/17
                    ->orderBy('controlusers.customer_id', 'asc')    //2022/10/17
                    // sortable()を追加
                    ->sortable()
                    ->paginate(300);
            }
        } else {
            if($organization_id == 0) {
                $controlusers = ControlUser::where('organization_id','>=',$organization_id)
                // 削除されていない
                ->whereNull('deleted_at')
                //user_idカラムの値が〇〇、もしくは✕✕の情報を取得
                ->whereIn('controlusers.user_id',$detail)
                ->orderBy('controlusers.user_id', 'asc')    //2022/10/17
                ->orderBy('controlusers.customer_id', 'asc')    //2022/10/17
                // sortable()を追加
                ->sortable()
                ->paginate(300);
            } else {
                $controlusers = ControlUser::where('organization_id','=',$organization_id)
                // 削除されていない
                ->whereNull('deleted_at')
                //user_idカラムの値が〇〇、もしくは✕✕の情報を取得
                ->whereIn('controlusers.user_id',$detail)
                ->orderBy('controlusers.user_id', 'asc')    //2022/10/17
                ->orderBy('controlusers.customer_id', 'asc')    //2022/10/17
                // sortable()を追加
                ->sortable()
                ->paginate(300);
            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));
        $common_no ='00_4';
        $keyword2  = $keyword;
        $compacts = compact( 'common_no','controlusers','organizations','users','customers','keyword2' );
        Log::info('controluser serch END');

        // return view('customer.index', ['customers' => $customers]);
        return view('controluser.index', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
            'user_id'  => [
                                    'min:1',        //指定された値以上か
                                    'integer',
                                    'required',
                                ],
            'customer_id'     => [
                                    'min:1',        //指定された値以上か
                                    'integer',
                                    'required',
                                    Rule::unique('controlusers')->whereNull('deleted_at')->ignore($id),
                                ],
        ];

        $messages = [
            'user_id.min'                     => 'ユーザーを選択してください。',
            'user_id.required'                => 'ユーザーは入力必須項目です。',
            'customer_id.min'                 => '顧客名を選択してください。',
            'customer_id.required'            => '顧客名は入力必須項目です。',
            'customer_id.unique'              => '顧客名が重複しています。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
    /**
     *
     */
}
