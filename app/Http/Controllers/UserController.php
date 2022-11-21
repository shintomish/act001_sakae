<?php

namespace App\Http\Controllers;

use Validator;
use DateTime;
use App\Models\User;
use App\Models\Organization;
use App\Models\Customer;
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

class UserController extends Controller
{
    //timestamps利用しない
    public $timestamps = false;

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
        Log::info('user index START');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;
        // $users = DB::table('users')
        // $users = User::where('organization_id','=',$organization_id)
                // 組織の絞り込み
                // ->when($organization_id > 0, function ($query) use ($organization_id) {
                //     return $query->where('organization_id',$organization_id);
                // })
                // ->whereNull('deleted_at')
                 // sortable()を追加
                // ->sortable()
                // ->paginate(300);

        if($organization_id == 0) {
            $users = User::select(
                'users.id as id'
                ,'users.user_id as user_id'
                ,'users.name as name'
                ,'users.email as email'
                ,'users.organization_id as organization_id'
                ,'users.login_flg as login_flg'
                ,'users.admin_flg as admin_flg'
                ,'customers.id as customers_id'
                ,'customers.business_name as business_name'
                )
                ->leftJoin('customers', function ($join) {
                    $join->on('customers.id', '=', 'users.user_id');
                })
                    // 組織の絞り込み
                    // ->where('users.organization_id','=',$organization_id)
                    // 削除されていない
                    ->whereNull('users.deleted_at')
                    ->whereNull('customers.deleted_at')
                    // ($keyword)の絞り込み '%'.$keyword.'%'
                    // sortable()を追加
                    ->sortable('id','business_name')
                    // ->orderBy('users.id', 'desc')
                    ->paginate(300);
        } else {
            $users = User::select(
                'users.id as id'
                ,'users.user_id as user_id'
                ,'users.name as name'
                ,'users.email as email'
                ,'users.organization_id as organization_id'
                ,'users.login_flg as login_flg'
                ,'users.admin_flg as admin_flg'
                ,'customers.id as customers_id'
                ,'customers.business_name as business_name'
                )
                ->leftJoin('customers', function ($join) {
                    $join->on('customers.id', '=', 'users.user_id');
                })
                    // 組織の絞り込み
                    ->where('users.organization_id','=',$organization_id)
                    // 削除されていない
                    ->whereNull('users.deleted_at')
                    ->whereNull('customers.deleted_at')
                    // ($keyword)の絞り込み '%'.$keyword.'%'
                    // sortable()を追加
                    ->sortable('id','business_name')
                    // ->orderBy('users.id', 'desc')
                    ->paginate(300);
        }

        // 一覧の組織IDを組織名にするため organizationsを取得
        $organizations = DB::table('organizations')
                // 組織の絞り込み
                ->when($organization_id != 0, function ($query) use ($organization_id) {
                    return $query->where( 'id', $organization_id );
                })
                // 削除されていない
                ->whereNull('deleted_at')
                ->get();

        // customersを取得
        $customers = DB::table('customers')
                // 組織の絞り込み
                // ->when($organization_id != 0, function ($query) use ($organization_id) {
                //     return $query->where( 'id', $organization_id );
                // })
                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                // ->where('active_cancel','!=', 3)
                // 削除されていない
                ->whereNull('deleted_at')
                // 2021/12/13
                ->orderBy('customers.business_name', 'asc')
                ->get();

        $common_no ='00_1';
        $keyword   = null;
        $keyword2  = null;

        $compacts = compact( 'common_no','users', 'organizations','organization_id','customers','keyword','keyword2' );

        Log::info('user index END');
        return view( 'user.index', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('user create START');

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

        // customersを取得
        $customers = DB::table('customers')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            // 2021/12/13
                            ->orderBy('business_name', 'asc')
                            ->get();

        $compacts = compact( 'organizations', 'organization','organization_id','customers' );

        Log::info('user create END');
        return view( 'user.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('user store START');

        $organization = $this->auth_user_organization();
        $request->password = Hash::make($request->password);
        $request->merge(
            ['organization_id'=> $organization->id],
            ['password' => Hash::make($request->password)],
            ['user_id'=> $request->user_id],
            ['login_flg'=> $request->login_flg],
            ['admin_flg'=> $request->admin_flg]
        );
        // $request->merge( ['organization_id'=> $organization->id] );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('user/create')->withErrors($validator)->withInput();
        }
    //  Log::debug('user store $request = ' . print_r($request->all(), true));

        // 重複クリック対策
        $request->session()->regenerateToken();

        DB::beginTransaction();
        Log::info('beginTransaction - user store start');
        try {
            // User::create($request->all());
            $user = new User();
            $user->password        = Hash::make($request->password);
            $user->name            = $request->name;
            $user->email           = $request->email;
            $user->organization_id = $request->organization_id;
            $user->user_id         = $request->user_id;
            $user->login_flg       = $request->login_flg;
            $user->admin_flg       = $request->admin_flg;
            $user->save();         //  Inserts
            DB::commit();
            Log::info('beginTransaction - user store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - user store end(rollback)');
        }

        Log::info('user store END');
        // return redirect()->route('user.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('user.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('user show START');
        Log::info('user show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('user edit START');

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

        // userを取得
        $user = User::find($id);

        // customerを取得
        $customers = DB::table('customers')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            // ->where('active_cancel','!=', 3)
                            // 削除されていない
                            ->whereNull('deleted_at')
                            // 組織の絞り込み
                            // ->when($organization_id != 0, function ($query) use ($organization_id) {
                            //     return $query->where( 'id', $organization_id );
                            // })
                            // 2021/12/13
                            ->orderBy('customers.business_name', 'asc')
                            ->get();

        $compacts = compact( 'organizations', 'organization', 'organization_id','user','customers' );

        Log::info('user edit END');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('user.edit', $compacts );
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
        Log::info('user update START');

        $validator = $this->get_validator($request,$id);
        if ($validator->fails()) {
            return redirect('user/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $user = User::find($id);

        // 重複クリック対策
        $request->session()->regenerateToken();

        DB::beginTransaction();
        Log::info('beginTransaction - user update start');
        try {
            // $user->username = $request->username;
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->user_id  = $request->user_id;
            $user->login_flg  = $request->login_flg;
            $user->admin_flg  = $request->admin_flg;
            if($request->filled('password')) { // パスワード入力があるときだけ変更
                // $user->password = bcrypt($request->password);
                $user->password = Hash::make($request->password);
                //Hash::make($request->newPassword)
            }
            $result = $user->save();
            DB::commit();
            Log::info('beginTransaction - user update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - user update end(rollback)');
        }

        Log::info('user update END');
        // return redirect()->route('user.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('user.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('user destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - user destroy start');
        //return redirect(route('user.index'))->with('msg_danger', '許可されていない操作です');

        try {
            // User::where('id', $id)->delete();
            $user = User::find($id);
            $user->deleted_at     = now();
            $result = $user->save();
            DB::commit();
            Log::info('beginTransaction - user destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - user destroy end(rollback)');
        }

        Log::info('destroy END');
        // return redirect()->route('user.index')->with('msg_success', '削除完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('user.index');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(User $customer, Request $request)
    {
        Log::info('user serch START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');      //名前検索
        $keyword2 = $request->Input('keyword2');    //顧客名検索

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 名前　顧客名が入力された
        if($keyword || $keyword2) {
            $users = User::select(
                 'users.id as id'
                ,'users.user_id as user_id'
                ,'users.name as name'
                ,'users.email as email'
                ,'users.organization_id as organization_id'
                ,'users.login_flg as login_flg'
                ,'users.admin_flg as admin_flg'
                ,'customers.id as customers_id'
                ,'customers.business_name as business_name'
            )
            ->leftJoin('customers', function ($join) {
                $join->on('customers.id', '=', 'users.user_id');
            })
                // 削除されていない
                ->whereNull('users.deleted_at')
                ->whereNull('customers.deleted_at')
                // ($keyword)の絞り込み '%'.$keyword.'%'
                ->where('users.name', 'like', "%$keyword%") // 2022/09/20
                ->where('customers.business_name', 'like', "%$keyword2%") // 2022/09/29
                // sortable()を追加
                ->sortable()
                ->paginate(300);

        } else {
            if($organization_id == 0) {
                $users = User::where('organization_id','>=',$organization_id)
                    // 削除されていない
                    ->whereNull('deleted_at')
                    // sortable()を追加
                    ->sortable()
                    ->paginate(300);

            } else {
                $users = User::where('organization_id','=',$organization_id)
                    // 削除されていない
                    ->whereNull('deleted_at')
                    // sortable()を追加
                    ->sortable()
                    ->paginate(300);

            }
        };

        // 一覧の組織IDを組織名にするため organizationsを取得
        $organizations = DB::table('organizations')
                // 組織の絞り込み
                ->when($organization_id != 0, function ($query) use ($organization_id) {
                    return $query->where( 'id', $organization_id );
                })
                // 削除されていない
                ->whereNull('deleted_at')
                ->get();

        // customersを取得
        $customers = DB::table('customers')
                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                // ->where('active_cancel','!=', 3)
                // 削除されていない
                ->whereNull('deleted_at')
                ->get();

        //  $data =  $this->jsonResponse($customers);
        //  Log::debug('user index $customers = ' . print_r($customers, true));
        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        $common_no ='00_1';
        $keyword   = $keyword;
        $keyword2  = $keyword2;
        $compacts = compact( 'common_no','users', 'organizations','organization_id','customers','keyword','keyword2' );

        Log::info('user serch END');

        return view('user.index', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = ['organization_id' => 'required',
                    'name'            => [
                                            'required',
                                            Rule::unique('users','name')->ignore($id)->whereNull('deleted_at'),
                                         ],
                    'user_id'         =>    'required',
                    'login_flg'       => [
                                            'min:1',        //指定された値以上か
                                            // 'regex:/^[顧客|社員|所属]+$/u',
                                            'integer',
                                            'required',
                                         ],
                    'admin_flg'       => [
                                            'min:1',        //指定された値以上か
                                            // 'regex:/^[顧客|社員|所属]+$/u',
                                            'integer',
                                            'required',
                                         ],
                    'email'           => [
                                            'required',
                                            Rule::unique('users','email')->ignore($id)->whereNull('deleted_at'),
                                         ],
                    'password'        =>    'confirmed',
                ];

        $messages = [
                     'organization_id.required' => '組織名は入力必須項目です。',
                     'name.required'            => 'ユーザー名は入力必須項目です。',
                     'name.unique'              => 'そのユーザー名は既に登録されています。',
                     'user_id.required'         => '顧客名は入力必須項目です。',
                     'login_flg.min'            => '利用区分は顧客|社員|所属から選択してください。',
                     'login_flg.required'       => '利用区分は入力必須項目です。',
                     'admin_flg.min'            => '管理区分は一般|管理者から選択してください。',
                     'admin_flg.required'       => '管理区分は入力必須項目です。',
                     'email.required'           => 'Eメールは入力必須項目です。',
                     'email.unique'             => 'そのEメールは既に登録されています。',
                     'password.confirmed'       => '確認用のパスワードと一致しません。',
                    ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
    public function jsonResponse($data, $code = 200)
    {
        return response()->json(
            $data,
            $code,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
}
