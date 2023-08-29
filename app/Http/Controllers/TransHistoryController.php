<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\ImageUpload;
use App\Models\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransHistoryController extends Controller
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
    public function index()
    {
        Log::info('transhistory index START');

        //FileNameは「latestinformation.pdf」固定 2022/09/24
        $books = DB::table('books')->first();
        $str   = ( new DateTime($books->info_date))->format('Y-m-d');
        $latestinfodate = '最新情報'.'('.$str.')';

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $u_id = $user->id;
        $organization_id =  $user->organization_id;

        // Customer(複数レコード)情報を取得する
        $customer_findrec = $this->auth_customer_findrec();
        $customer_id = $customer_findrec[0]['id'];

        // 2022/11/10
        $indiv_class = $customer_findrec[0]['individual_class'];

        // Log::debug('transhistory index  customer_findrec = ' . print_r($customer_findrec,true));

        // Customer(all)情報を取得する
        if($organization_id == 0) {
            $customers = Customer::whereNull('deleted_at');
                            // ->sortable()
                            // ->paginate(10);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at');
                            // ->sortable()
                            // ->paginate(10);
        }

        $imageuploads = Imageupload::where('user_id',$u_id)
            // 削除されていない
            ->whereNull('deleted_at')
            // 顧客の絞り込み
            ->where('customer_id',$customer_id)
            // sortable()を追加
            ->sortable()
            ->orderByRaw('created_at DESC')
            ->paginate(10);

        $keyword = null;

        Log::info('transhistory index END');

        $compacts = compact( 'indiv_class','imageuploads','customers','customer_findrec','customer_id','latestinfodate','keyword' );

        return view( 'transhistory.index', $compacts );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(ImageUpload $imageupload, Request $request)
    {
        Log::info('transhistory serch START');

        //FileNameは「latestinformation.pdf」固定 2022/09/24
        $books = DB::table('books')->first();
        $str   = ( new DateTime($books->info_date))->format('Y-m-d');
        $latestinfodate = '最新情報'.'('.$str.')';

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');
        $customer_id = $request->Input('customer_id');

        // 2022/11/10
        // Customer(複数レコード)情報を取得する
        $customer_findrec = $this->auth_customer_findrec();

        // 2022/11/30
        $customers = Customer::where('id',$customer_id)
            ->orderBy('id', 'asc')
            ->first();

        // 2022/11/30
        // 2022/11/10
        // $indiv_class = $customer_findrec[0]['individual_class'];
        $indiv_class = $customers->individual_class;

// Log::debug('transhistory serch  keyword     = ' . print_r($keyword,true));
// Log::debug('transhistory serch  customer_id = ' . print_r($customer_id,true));
        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $u_id = $user->id;
        $organization_id =  $user->organization_id;
        // 日付が入力された
        if($keyword) {
            $imageuploads = Imageupload::where('user_id',$u_id)
            // 削除されていない
            ->whereNull('deleted_at')
            // ($keyword)日付の絞り込み
            ->whereDate('created_at',$keyword)
            // ($keyword)顧客の絞り込み
            ->where('customer_id',$customer_id)
            // sortable()を追加
            ->sortable()
            ->orderByRaw('created_at DESC')
            ->paginate(10);
        } else {
            $imageuploads = Imageupload::where('user_id',$u_id)
            // 削除されていない
            ->whereNull('deleted_at')
            // ($keyword)顧客の絞り込み
            ->where('customer_id',$customer_id)
            // sortable()を追加
            ->sortable()
            ->orderByRaw('created_at DESC')
            ->paginate(10);
        };

        // Customer(複数レコード)情報を取得する
        $customer_findrec = $this->auth_customer_findrec();

        // Customer(all)情報を取得する
        if($organization_id == 0) {
            $customers = Customer::whereNull('deleted_at');
                            // ->sortable()
                            // ->paginate(10);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at');
                            // ->sortable()
                            // ->paginate(10);
        }

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        $compacts = compact( 'indiv_class','imageuploads','customers','customer_findrec','customer_id','latestinfodate','keyword' );

        Log::info('transhistory serch END');
        return view( 'transhistory.index', $compacts );
    }

    /**
     * Display a listing of the resource.
     * 未使用
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(ImageUpload $imageupload, Request $request)
    {
        Log::info('transhistory serch_custom START');

        //FileNameは「latestinformation.pdf」固定 2022/09/24
        $books = DB::table('books')->first();
        $str   = ( new DateTime($books->info_date))->format('Y-m-d');
        $latestinfodate = '最新情報'.'('.$str.')';

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $customer_id = $request->Input('customer_id');
        // Log::debug('transhistory serch_custom  customer_id = ' . print_r($customer_id,true));

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $u_id = $user->id;
        $organization_id =  $user->organization_id;
        // 顧客が選択された
        if($customer_id) {
            $imageuploads = Imageupload::where('user_id',$u_id)
                // 削除されていない
                ->whereNull('deleted_at')
                // ($keyword)顧客の絞り込み
                ->where('customer_id',$customer_id)
                // sortable()を追加
                ->sortable()
                ->orderByRaw('created_at DESC')
                ->paginate(10);
        } else {
            $imageuploads = Imageupload::where('user_id',$u_id)
                // 削除されていない
                ->whereNull('deleted_at')
                // sortable()を追加
                ->sortable()
                ->orderByRaw('created_at DESC')
                ->paginate(10);
        };

        // Customer(複数レコード)情報を取得する
        $customer_findrec = $this->auth_customer_findrec();

        // Customer(all)情報を取得する
        if($organization_id == 0) {
            $customers = Customer::whereNull('deleted_at');
                            // ->sortable()
                            // ->paginate(10);
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at');
                            // ->sortable()
                            // ->paginate(10);
        }

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        $compacts = compact( 'imageuploads','customers','customer_findrec','customer_id','latestinfodate' );

        Log::info('transhistory serch_custom END');
        return view( 'transhistory.index', $compacts );
    }

}
