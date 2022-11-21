<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Organization;
use App\Models\Parameter;

use App\Http\Requests\StoreOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
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
        Log::info('organization index START');

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;
        $organizations = DB::table('organizations')
                        // 組織の絞り込み
                        ->when($organization_id > 0, function ($query) use ($organization_id) {
                            return $query->where('id',$organization_id);
                        })
                        ->whereNull('deleted_at')
                        ->paginate(10);

        Log::info('organization index END');
        return view('organization.index', compact('organizations','organization'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('organization create START');
        $organization = $this->auth_user_organization();
        $compacts = compact( 'organization' );
        Log::info('organization create END');

        return view('organization.create',$compacts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('organization store START');

        $validator = $this->get_validator($request);
        if ($validator->fails()) {
            return redirect('organization/create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        Log::info('beginTransaction - organization store start');
        try {
            Organization::create($request->all());
            DB::commit();
            Log::info('beginTransaction - organization store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - organization end(rollback)');
        }

        Log::info('organization store END');
        // return redirect()->route('organization.index')->with('msg_success', '新規登録完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('organization.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('organization show CALLED');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('organization edit START');

        $organization_id = $id;
        $organization = Organization::find($id);

        Log::info('organization edit END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));

        return view('organization.edit', compact('organization','organization_id'));
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
        Log::info('organization update START');

        $validator = $this->get_validator($request);
        if ($validator->fails()) {
            return redirect('organization/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $update = [
            'name'       => $request->name,
            'kana'       => $request->kana,
            'first_code' => $request->first_code,
            'last_code'  => $request->last_code,
            'prefecture' => $request->prefecture,
            'city'       => $request->city,
            'address'    => $request->address,
            'other'      => $request->other,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'comment'    => $request->comment,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        DB::beginTransaction();
        Log::info('beginTransaction - organization update start');
        try {
            Organization::where('id', $id)->update($update);
            DB::commit();
            Log::info('beginTransaction - organization update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - organization update end(rollback)');
        }

        Log::info('organization END');
        // return redirect()->route('organization.index')->with('msg_success', '編集完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('organization.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('organization destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - destroy start');
        try {
            Organization::where('id', $id)->delete();
            DB::commit();
            Log::info('beginTransaction - organization destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - organization destroy end(rollback)');
        }

        Log::info('organization destroy END');
        // return redirect()->route('organization.index')->with('msg_success', '削除完了');
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('organization.index');
    }

    /**
     *
     */
    public function get_validator(Request $request)
    {
        $rules   = ['name'       => 'required',
                    'kana'       => 'required',
                    'first_code' => 'required|digits:3',
                    'last_code'  => 'required|digits:4',
                    'prefecture' => 'required|max:255',
                    'city'       => 'required|max:255',
                    'address'    => 'required|max:255',
                    'phone'      => 'required|alpha_dash|max:255',
                    'email'      => 'required|email',
                    'comment'    => 'max:255',
                ];

        $messages = ['name.required'       => '組織名は入力必須項目です。',
                     'kana.required'       => '組織名(読み)は入力必須項目です。',
                     'first_code.required' => '郵便番号(3桁)は入力必須項目です。',
                     'first_code.digits'   => '郵便番号(3桁)に入力誤りがあります。',
                     'last_code.required'  => '郵便番号(4桁)は入力必須項目です。',
                     'last_code.digits'    => '郵便番号(4桁)に入力誤りがあります。',
                     'prefecture.required' => '都道府県は入力必須項目です。',
                     'city.required'       => '市区町村は入力必須項目です。',
                     'address.required'    => '町域・番地は入力必須項目です。',
                     'phone.required'      => '電話番号は入力必須項目です。',
                     'phone.alpha_dash'    => '電話番号に入力誤りがあります。',
                     'phone.max'           => '電話番号に入力誤りがあります。',
                     'email.required'      => 'Eメールは入力必須項目です。',
                     'email.email'         => 'Eメールに入力誤りがあります。',
                     'comment.max'         => 'コメントは:max文字以内で入力してください。',
                    ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }
}
