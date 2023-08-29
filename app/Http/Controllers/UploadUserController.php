<?php

namespace App\Http\Controllers;

use DateTime;

use App\Models\UploadUser;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UploadUserController extends Controller
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
    // public function index(UploadUser $uploaduser) 2022/12/12
    public function index()
    {
        Log::info('uploaduser index START');

        // 2022/12/12
        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 年月取得
        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        $dateNew = ($now->format('Y/m'));

        $uploadusers = DB::table('uploadusers')
                    ->where('organization_id','>=',$organization_id) // 2022/12/12
                    ->whereNull('deleted_at')
                    ->where('check_flg', 2)     // ファイル無し(1):ファイル有り(2)
                    ->get();

        $data['count'] = $uploadusers->count();

        //更新
        if( $data['count'] > 0 ) {
            foreach($uploadusers as $key => $val) {
                $foldername  = $val->foldername;
                $folderpath  = 'app/userdata/' . $foldername. '/*.*';

                // dir配下のファイル一覽を取得する
                $dir   = storage_path($folderpath);
                $files = glob($dir);
// Log::debug('uploaduser file_check $files = ' .print_r($files,true));
                if (empty($files)) {
                    $up_users = DB::table('uploadusers')
                                ->where('id',$val->id)
                                ->update([
                                    'yearmonth'  =>  $dateNew,
                                    // {{-- ファイル無し(1):ファイル有り(2) --}}
                                    'check_flg'  =>  1,
                                    // 優先順位 -(1): 低(2): 中(3): 高(4) 2022/11/05
                                    // 'prime_flg'  =>  1,
                                    // 'updated_at' =>  now()
                                ]);
// Log::debug('uploaduser file_check $dateNew = ' .print_r($dateNew,true));
                }
            }
        } else {
            // 対象データがありません
            // session()->flash('toastr', config('toastr.csv_warning'));
        }

        // Customer情報を取得する
        $customers  = $this->auth_customer_all();

        // $uploadusers = $uploaduser 2022/12/12
        $uploadusers = Uploaduser::where('organization_id','>=',$organization_id)
                    ->whereNull('deleted_at')
                    ->sortable()
                    ->orderBy('prime_flg', 'desc')
                    ->orderBy('updated_at', 'asc')
                    ->orderBy('check_flg', 'desc')
                    ->paginate(300);

        $common_no = '01';
        $keyword2  = null;
        $individual_mail = 3;
        $compacts = compact( 'uploadusers', 'customers','common_no','keyword2','individual_mail' );

        Log::info('uploaduser index END');
        return view('uploaduser.index', $compacts );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function custum(UploadUser $uploaduser,Request $request) 2022/12/12
    public function custum(Request $request)
    {
        Log::info('uploaduser custum START');

        // 2022/12/12
        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 年月取得
        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        $dateNew = ($now->format('Y/m'));

        $uploadusers = DB::table('uploadusers')
                    ->where('organization_id','>=',$organization_id) // 2022/12/12
                    ->whereNull('deleted_at')
                    ->where('check_flg', 2)     // ファイル無し(1):ファイル有り(2)
                    ->get();

        $data['count'] = $uploadusers->count();

        //更新
        if( $data['count'] > 0 ) {
            foreach($uploadusers as $key => $val) {
                $foldername  = $val->foldername;
                $folderpath  = 'app/userdata/' . $foldername. '/*.*';

                // dir配下のファイル一覽を取得する
                $dir   = storage_path($folderpath);
                $files = glob($dir);
// Log::debug('uploaduser file_check $files = ' .print_r($files,true));
                if (empty($files)) {
                    $up_users = DB::table('uploadusers')
                                ->where('id',$val->id)
                                ->update([
                                    'yearmonth'  =>  $dateNew,
                                    // {{-- ファイル無し(1):ファイル有り(2) --}}
                                    'check_flg'  =>  1,
                                    // 'updated_at' =>  now()
                                ]);
// Log::debug('uploaduser file_check $dateNew = ' .print_r($dateNew,true));
                }
            }
        } else {
            // 対象データがありません
            // session()->flash('toastr', config('toastr.csv_warning'));
        }

        // Customer情報を取得する
        $customers  = $this->auth_customer_all();

        // 法人/個人
        $individual_mail = $request->input('individual_mail');
// Log::debug('uploaduser custum $individual_mail = ' .print_r($individual_mail,true));

        if($individual_mail == 3){
            $_indiv = [1,2];
        } else {
            $_indiv = [$individual_mail];
        }

        // $organization_id = 1; 2022/12/12
        $uploadusers = Uploaduser::select(
                    'uploadusers.id as id'
                    ,'uploadusers.foldername as foldername'
                    ,'uploadusers.business_name as business_name'
                    ,'uploadusers.organization_id as organization_id'
                    ,'uploadusers.customer_id as customer_id'
                    ,'uploadusers.yearmonth as yearmonth'
                    ,'uploadusers.check_flg as check_flg'
                    ,'uploadusers.prime_flg as prime_flg'
                    // 2023/08/18
                    ,'uploadusers.txt_memo as txt_memo'
                    ,'customers.id as cus_id'
                )
                ->leftJoin('customers', function ($join) {
                    $join->on('uploadusers.customer_id', '=', 'customers.id');
                })
                ->where('customers.organization_id','>=',$organization_id) // 2022/12/12
                ->where('customers.active_cancel','!=', 3)      // `active_cancel` 1:契約 2:SPOT 3:解約',
                // ->where('customers.notificationl_flg','=',2 )   // 通知しない(1):通知する(2)
                ->whereIn('customers.individual_class',$_indiv )
                ->sortable()
                ->orderBy('uploadusers.prime_flg', 'desc')
                ->orderBy('uploadusers.updated_at', 'asc')
                ->orderBy('uploadusers.check_flg', 'desc')
                ->paginate(300);

        $common_no = '01';
        $keyword2  = null;

        $compacts = compact( 'uploadusers', 'customers','common_no','keyword2','individual_mail' );

        Log::info('uploaduser custum END');

        return view('uploaduser.index', $compacts );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function serch(UploadUser $uploaduser, Request $request) 2022/12/12
    public function serch(Request $request)
    {
        Log::info('uploaduser serch START');

        // 2022/12/12
        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $u_id = $user->user_id;

        // 日付が入力された
        if($keyword) {
            // $uploadusers = $uploaduser 2022/12/12
            $uploadusers = Uploaduser::where('organization_id','>=',$organization_id)
                        // ($keyword)日付の絞り込み
                        ->whereDate('created_at',$keyword)
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('prime_flg', 'desc')
                        ->orderBy('updated_at', 'asc')
                        ->orderBy('check_flg', 'desc')
                        ->paginate(300);
        } else {
            // $uploadusers = $uploaduser 2022/12/12
            $uploadusers = Uploaduser::where('organization_id','>=',$organization_id)
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('prime_flg', 'desc')
                        ->orderBy('updated_at', 'asc')
                        ->orderBy('check_flg', 'desc')
                        ->paginate(300);
        };
        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        Log::info('uploaduser serch END');

        return view('uploaduser.index', ['uploadusers' => $uploadusers]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function serch_customer(UploadUser $uploaduser, Request $request) 2022/12/12
    public function serch_customer(Request $request)
    {
        Log::info('uploaduser serch_customer START');

        // 2022/12/12
        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $u_id = $user->user_id;

        // 日付が入力された
        if($keyword) {
            // $uploadusers = $uploaduser 2022/12/12
            $uploadusers = Uploaduser::where('organization_id','>=',$organization_id)
                        // ($keyword)の絞り込み
                        ->where('business_name', 'like', "%$keyword%")
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('prime_flg', 'desc')
                        ->orderBy('updated_at', 'asc')
                        ->orderBy('check_flg', 'desc')
                        ->paginate(300);
        } else {
            // $uploadusers = $uploaduser 2022/12/12
            $uploadusers = Uploaduser::where('organization_id','>=',$organization_id)
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('prime_flg', 'desc')
                        ->orderBy('updated_at', 'asc')
                        ->orderBy('check_flg', 'desc')
                        ->paginate(300);
        };
        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        Log::info('uploaduser serch_customer END');
        $common_no = '01';
        $keyword2  = $keyword;
        $individual_mail = 3;

        $compacts = compact( 'uploadusers','common_no','keyword2','individual_mail' );

        return view('uploaduser.index',  $compacts );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function file_check(UploadUser $uploaduser) 2022/12/12
    public function file_check(UploadUser $uploaduser)
    {
        Log::info('uploaduser file_check START');

        // 2022/12/12
        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 年月取得
        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        $dateNew = ($now->format('Y/m'));

        $uploadusers = DB::table('uploadusers')
                    ->where('organization_id','>=',$organization_id) // 2022/12/12
                    ->whereNull('deleted_at')
                    // ->where('check_flg', 2)     // ファイル無し(1):ファイル有り(2) 2022/12/13
                    ->get();

        $data['count'] = $uploadusers->count();

        //更新
        if( $data['count'] > 0 ) {
            foreach($uploadusers as $key => $val) {
                $foldername  = $val->foldername;
                $folderpath  = 'app/userdata/' . $foldername. '/*.*';

                // dir配下のファイル一覽を取得する
                $dir   = storage_path($folderpath);
                $files = glob($dir);
                if (empty($files)) {
                    $check_flg = 1;     //無し(1)
                } else {
                    $check_flg = 2;     //有り(2)
                }
// Log::debug('uploaduser file_check $dir       = ' .print_r($dir,true));
// Log::debug('uploaduser file_check $check_flg = ' .print_r($check_flg,true));
                $up_users = DB::table('uploadusers')
                    ->where('id',$val->id)
                    ->update([
                        'yearmonth'  =>  $dateNew,
                        // {{-- ファイル無し(1):ファイル有り(2) --}}
                        // 'check_flg'  =>  1, 2022/12/13
                        'check_flg'  =>  $check_flg,
                        // 'updated_at' =>  now()
                    ]);
            }
        } else {
            // 対象データがありません
            session()->flash('toastr', config('toastr.csv_warning'));
        }

        // Customer情報を取得する
        $customers  = $this->auth_customer_all();

        $uploadusers = Uploaduser::where('organization_id','>=',$organization_id)
                    ->whereNull('deleted_at')
                    ->sortable()
                    ->orderBy('prime_flg', 'desc')
                    ->orderBy('updated_at', 'asc')
                    ->orderBy('check_flg', 'desc')
                    ->paginate(300);

        $common_no = '01';
        $keyword2  = null;
        $individual_mail = 3;
        $compacts = compact( 'uploadusers', 'customers','common_no','keyword2','individual_mail' );

        Log::info('uploaduser file_check END');

        return view('uploaduser.index', $compacts );
    }
    /**
     * [webapi]Uploaduserテーブルの更新
     */
    public function update_api(Request $request)
    {
        Log::info('update_api Uploaduser START');

        // Log::debug('update_api request = ' .print_r($request->all(),true));
        $id = $request->input('id');

        $prime_flg      = $request->input('prime_flg');
        // 2023/08/18
        $txt_memo       = $request->input('txt_memo');

        // Log::debug('prime_flg       : ' . $prime_flg);
        // Log::debug('txt_memo        : ' . $txt_memo);

        $counts = array();
        $update = [];
        if( $request->exists('prime_flg')      ) $update['prime_flg']     = $request->input('prime_flg');
        // 2023/08/18
        if( $request->exists('txt_memo')       ) $update['txt_memo']      = $request->input('txt_memo');

        // $update['updated_at'] = date('Y-m-d H:i:s');
        // Log::debug('update_api update : ' . print_r($update,true));

        $status = array();
        DB::beginTransaction();
        Log::info('update_api Uploaduser beginTransaction - start');
        try{
            // 更新処理
            Uploaduser::where( 'id', $id )->update($update);

            $status = array( 'error_code' => 0, 'message'  => 'Your data has been changed!' );

            DB::commit();
            Log::info('update_api Uploaduser beginTransaction - end');
        }
        catch(Exception $e){
            Log::error('update_api Uploaduser exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('update_api Uploaduser beginTransaction - end(rollback)');
            echo "エラー：" . $e->getMessage();
            $status = array( 'error_code' => 501, 'message'  => $e->getMessage() );
        }

        Log::info('update_api Uploaduser END');
        return response()->json([ compact('status','counts') ]);
    }

}
