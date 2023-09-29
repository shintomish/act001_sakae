<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Parameter;
use Carbon\Carbon;
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

class NotaccountController extends Controller
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
        Log::info('noyaccount index START');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 4ヶ月前
        $date = new Carbon(now());
        $old = $date->subMonths(4);

        if($organization_id == 0) {
            $customers = Customer::whereNull('deleted_at')
                            ->whereDate('final_accounting_at', '<', $old)
                            ->orWhereNull('final_accounting_at')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            // 2023/09/29 add
                            ->orderBy('individual_class', 'asc')
                            ->orderBy('business_name', 'asc')
                            ->sortable()
                            ->paginate(300);
            $customers3 = Customer::whereNull('deleted_at')
                            ->whereDate('final_accounting_at', '<', $old)
                            ->orWhereNull('final_accounting_at')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3);
            $count3     = $customers3->count();
        } else {
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereDate('final_accounting_at', '<', $old)
                            ->orWhereNull('final_accounting_at')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->whereNull('deleted_at')
                            // 2023/09/29 add
                            ->orderBy('individual_class', 'asc')
                            ->orderBy('business_name', 'asc')
                            ->sortable()
                            ->paginate(300);
            $customers3 = Customer::where('organization_id','=',$organization_id)
                            ->whereDate('final_accounting_at', '<', $old)
                            ->orWhereNull('final_accounting_at')
                            // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->whereNull('deleted_at');
            $count3     = $customers3->count();
        }
        $common_no = '05';

        $keyword2  = null;
        $compacts = compact( 'common_no','customers', 'organization', 'organization_id','keyword2','count3' );
        Log::info('notaccount index END');
        return view( 'notaccount.index', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('notaccount create START');
        Log::info('notaccount create END');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('notaccount store START');


        Log::info('notaccount store END');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('notaccount show START');
        Log::info('notaccount show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('notaccount edit START');
        Log::info('notaccount edit END');
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
        Log::info('notaccount update START');
        Log::info('notaccount update END');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('notaccount destroy START');
        Log::info('notaccount destroy  END');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch_custom(Customer $customer, Request $request)
    {
        Log::info('notaccount serch_custom START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $keyword = $request->Input('keyword');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 4ヶ月前
        $date = new Carbon(now());
        $old = $date->subMonths(4);

        // 日付が入力された
        if($keyword) {
            if($organization_id == 0) {
                $customers = Customer::where('organization_id','>=',$organization_id)
                    // ($keyword)の絞り込み
                    ->where('business_name', 'like', "%$keyword%")
                    ->whereNull('deleted_at')
                    // ->whereDate('final_accounting_at', '<', $old)
                    // ->orWhereNull('final_accounting_at')
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->sortable()
                    ->paginate(300);
                $customers3 = Customer::where('organization_id','>=',$organization_id)
                    // ($keyword)の絞り込み
                    ->where('business_name', 'like', "%$keyword%")
                    ->whereNull('deleted_at')
                    // ->whereDate('final_accounting_at', '<', $old)
                    // ->orWhereNull('final_accounting_at')
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3);
                $count3     = $customers3->count();
            } else {
                $customers = Customer::where('organization_id','=',$organization_id)
                    // ($keyword)の絞り込み
                    ->where('business_name', 'like', "%$keyword%")
                    ->whereNull('deleted_at')
                    // ->whereDate('final_accounting_at', '<', $old)
                    // ->orWhereNull('final_accounting_at')
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    ->sortable()
                    ->paginate(300);
                $customers3 = Customer::where('organization_id','=',$organization_id)
                    // ($keyword)の絞り込み
                    ->where('business_name', 'like', "%$keyword%")
                    ->whereNull('deleted_at')
                    // ->whereDate('final_accounting_at', '<', $old)
                    // ->orWhereNull('final_accounting_at')
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3);
                $count3     = $customers3->count();
            }
        } else {
            if($organization_id == 0) {
                $customers = Customer::where('organization_id','>=',$organization_id)
                    ->whereDate('final_accounting_at', '<', $old)
                    ->orWhereNull('final_accounting_at')
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    // 削除されていない
                    ->whereNull('deleted_at')
                    ->sortable()
                    ->paginate(300);
                $customers3 = Customer::where('organization_id','>=',$organization_id)
                    ->whereDate('final_accounting_at', '<', $old)
                    ->orWhereNull('final_accounting_at')
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    // 削除されていない
                    ->whereNull('deleted_at');
                $count3     = $customers3->count();

            } else {
                $customers = Customer::where('organization_id','=',$organization_id)
                    ->whereDate('final_accounting_at', '<', $old)
                    ->orWhereNull('final_accounting_at')
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    // 削除されていない
                    ->whereNull('deleted_at')
                    ->sortable()
                    ->paginate(300);
                $customers3 = Customer::where('organization_id','=',$organization_id)
                    ->whereDate('final_accounting_at', '<', $old)
                    ->orWhereNull('final_accounting_at')
                    // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                    ->where('active_cancel','!=', 3)
                    // 削除されていない
                    ->whereNull('deleted_at');
                $count3     = $customers3->count();

            }
        };

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.serch'));

        $common_no = '05';
        $keyword2  = $keyword;

        $compacts = compact( 'common_no','customers','keyword2','count3');
        Log::info('notaccount serch_custom END');

        // return view('notaccount.index', ['customers' => $customers]);
        return view('notaccount.index', $compacts);
    }

    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [

        ];

        $messages = [


        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }

}
