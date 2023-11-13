<?php

namespace App\Http\Controllers;

use DateTime;
use Validator;
use App\Models\Customer;
use App\Models\Newsrepo;

use Carbon\Carbon;

use App\Mail\MailSend;      //Mailableクラス
use Mail;

// 下記を追記
use App\Services\MailAttachmentService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NewsrepoController extends Controller
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
        Log::info('newsrepos index START');

        // ログインユーザーのユーザー情報を取得する
        $user    = $this->auth_user_info();
        $user_id = $user->id;

        // 今月の月を取得
        // $nowmonth = intval($this->get_now_month());
        // 2022/11/11
        $nowmonth = 13;

        $organization  = $this->auth_user_organization();
        $organization_id = $organization->id;

        if($organization_id == 0) {
            $newsrepos = Newsrepo::whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('created_at', 'desc')
                        ->paginate(300);
        } else {
            $newsrepos = Newsrepo::where('organization_id','=',$organization_id)
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('created_at', 'desc')
                        ->paginate(300);
        }

        $common_no = '02';
    //  Log::debug('user index $users = ' . print_r($users, true));
        $compacts = compact( 'user_id','common_no','newsrepos' ,'organization_id','nowmonth');

        Log::info('newsrepo index END');
        return view( 'newsrepo.index', $compacts );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Log::info('newsrepo create START');

        // ログインユーザーのユーザー情報を取得する
        $user    = $this->auth_user_info();
        $user_id = $user->id;

        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 今月の月を取得
        // $nowmonth = intval($this->get_now_month());
        // 2022/11/11
        $nowmonth = 13;

        // Newsreposを取得
        if($organization_id == 0) {
            $newsrepos = Newsrepo::whereNull('deleted_at')
                        ->get();

            // customersを取得
            $customers = Customer::where('organization_id','>=',$organization_id)
                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                ->where('email','!=', '')             // 2022/10/17
                ->whereNotNull('email')               // 2022/10/24
                ->where('active_cancel','!=', 3)
                ->whereNull('deleted_at')
                ->orderBy('business_name', 'asc')
                ->get();
        } else {
            $newsrepos = Newsrepo::where('organization_id','=',$organization_id)
                        ->get();

            // customersを取得
            $customers = Customer::where('organization_id','=',$organization_id)
                // `active_cancel` int DEFAULT '1' COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
                ->where('email','!=', '')             // 2022/10/17
                ->whereNotNull('email')               // 2022/10/24
                ->where('active_cancel','!=', 3)
                ->whereNull('deleted_at')
                ->whereIn('individual_class',[1,2] )
                ->orderBy('business_name', 'asc')
                ->get();
        }
        $count          = $customers->count();
        $individual     = 3;
        $interim_mail   = $nowmonth;
        $announce_month = 1;
        $comment_out    = "";

        $compacts = compact( 'user_id','organization_id','newsrepos','nowmonth','customers','individual','interim_mail','announce_month','comment_out','count' );

        Log::info('newsrepo create END');
        return view( 'newsrepo.create', $compacts );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('newsrepo store START');

        // 2022/09/23
        if ($request->has('submit_new')) {
            return redirect()->route('newsrepo.create');
        }
        if ($request->has('submit_temp')) {
            return redirect()->route('newsrepo.create');
        }
        if ($request->has('submit_non')) {
            return redirect()->route('newsrepo.create');
        }

        $organization = $this->auth_user_organization();

        $request->merge(
            ['organization_id'  => $organization->id],
            ['comment'          => $request->comment],
            ['mail_flg'         => $request->mail_flg],
            ['individual_mail'  => $request->individual_mail],
            ['interim_mail'     => $request->interim_mail],
            ['announce_month'   => $request->announce_month],
        );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
    //  Log::debug('newsrepo store $request = ' . print_r($request->all(), true));
            return redirect('newsrepo/create')->withErrors($validator)->withInput();
        }

    //  Log::debug('newsrepo store $request = ' . print_r($request->all(), true));
        DB::beginTransaction();
        Log::info('beginTransaction - newsrepo store start');
        // 2022/12/31
        $indiv = $request->individual_mail;
        try {
            // 2022/12/31
            $query = '';
            $query .= 'UPDATE ';
            $query .= 'newsrepos ';
            $query .= 'SET newsrepos.urgent_flg = 1 ';
            $query .= 'WHERE  ';
            if($indiv == 3){
                $query .= 'newsrepos.individual_mail BETWEEN 1 AND 3 ';
            } elseif($indiv == 2) {
                $query .= 'newsrepos.individual_mail BETWEEN 2 AND 3 ';
            } elseif($indiv == 1) {
                $query .= 'newsrepos.individual_mail != 2  ';
            }
            $query .= 'AND newsrepos.deleted_at is NULL ';
            $query .= 'AND newsrepos.urgent_flg = 2 ';

            DB::update($query);
            // Log::debug('newsrepo store UPDATE $query = ' . $query);

            $newsrepo = new newsrepo();
            $newsrepo->organization_id   = $request->organization_id;
            $newsrepo->mail_flg          = 2;                           // 1:Mail 2:登録
            $newsrepo->individual_mail   = $request->individual_mail;
            $newsrepo->interim_mail      = $request->interim_mail;
            $newsrepo->announce_month    = $request->announce_month;
            $newsrepo->comment           = $request->comment;
            $newsrepo->file_name         = '';
            $newsrepo->urgent_flg        = 2;   //2022/12/30 至急フラグ(1):通常 (2):至急
            $newsrepo->updated_at        = now();
            $newsrepo->save();           //  Inserts
            DB::commit();
            Log::info('beginTransaction - newsrepo store end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - newsrepo store end(rollback)');
        }

        Log::info('newsrepo store END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.create'));
        return redirect()->route('newsrepo.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info('newsrepo show START');
        Log::info('newsrepo show END');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('newsrepo edit START');

        $organization    = $this->auth_user_organization();
        $organization_id = $organization->id;

        // newsrepoを取得
        $newsrepos = Newsrepo::find($id);

        $compacts = compact( 'organization_id','newsrepos' );

        Log::info('newsrepo edit END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.edit'));
        return view('newsrepo.edit', $compacts );
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
        Log::info('newsrepo update START');

        $validator = $this->get_validator($request,$id);
        if ($validator->fails()) {
            return redirect('newsrepo/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        $newsrepo = Newsrepo::find($id);

        DB::beginTransaction();
        Log::info('beginTransaction - newsrepo update start');
        try {
            $newsrepo->comment           = $request->comment;
            $newsrepo->mail_flg          = $request->mail_flg;
            $newsrepo->individual_mail   = $request->individual_mail;
            $newsrepo->interim_mail      = $request->interim_mail;
            $newsrepo->announce_month    = $request->announce_month;
            // $newsrepo->urgent_flg        = 2;   //2022/12/30 至急フラグ(1):通常 (2):至急
            $result = $newsrepo->save();
            DB::commit();
            Log::info('beginTransaction - newsrepo update end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - newsrepo update end(rollback)');
        }

        Log::info('newsrepo update END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.update'));
        return redirect()->route('newsrepo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info('newsrepo destroy START');

        DB::beginTransaction();
        Log::info('beginTransaction - newsrepo destroy start');

        try {
            $newsrepo = Newsrepo::find($id);
            $newsrepo->deleted_at     = now();
            $result = $newsrepo->save();
            DB::commit();
            Log::info('beginTransaction - newsrepo destroy end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - newsrepo destroy end(rollback)');
        }

        Log::info('newsrepo destroy END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.delete'));
        return redirect()->route('newsrepo.index');
    }
    /**
     *
     */
    public function get_validator(Request $request,$id)
    {
        $rules   = [
                    'comment'         => [
                                        'required',
                                        'max:2000', //2023/11/13 1000->2000 DB=2048
                                        ],
                ];

        $messages = [
                    'comment.required'         => 'コメントは入力必須項目です。',
                    'comment.max'              => 'コメントは2000文字までです。',
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
    public function send(Request $request)
    {
        Log::info('newsrepo send START');

        Log::info('newsrepo send END');
    }

    public function sendmail(Request $request)
    {
        Log::info('newsrepo sendmail START');

        // ログインユーザーのユーザー情報を取得する
        $user    = $this->auth_user_info();
        $user_id = $user->id;

        // ログインユーザーの組織情報を取得する
        $organization = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 今月の月を取得
        $nowmonth = intval($this->get_now_month());

        $request->merge(
            ['organization_id'  => $organization->id],
            ['user_id'          => $user_id],
            ['comment'          => $request->comment],
            ['mail_flg'         => $request->mail_flg],
            ['individual_mail'  => $request->individual_mail],
            ['interim_mail'     => $request->interim_mail],
            ['announce_month'   => $request->announce_month],
        );

        $validator = $this->get_validator($request,$request->id);
        if ($validator->fails()) {
            return redirect('newsrepo/create')->withErrors($validator)->withInput();
        }

        // 法人/個人
        $individual = $request->individual_mail;

        // 選択月
        $interim_mail = $request->interim_mail;

        // 告知月 (1):ー (2):決算月1ケ月前 (3):決算月1ケ月後 (4):決算月2ケ月後 (5):決算月7ケ月後
        $announce_month = $request->announce_month;
        if($announce_month == 1){
            $annmonth = [1,2,3,4,5,6,7,8,9,10,11,12,13];
        } else {
            if($announce_month == 2){
                // 2022/10/17
                // * 選択月($strmon)の１($mon)月後を取得
                $annmonth = [intval($this->getbase_specify_month($interim_mail, 1 ))];
            } elseif($announce_month == 3) {
                // 2022/10/17
                // * 選択月($strmon)の１($mon)月後を取得
                $annmonth = [intval($this->getbase_submonth($interim_mail, 1 ))];
            } elseif($announce_month == 4) {
                // 2022/10/17
                // * 選択月($strmon)の２($mon)月後を取得
                $annmonth = [intval($this->getbase_submonth($interim_mail, 2 ))];
            } elseif($announce_month == 5) {
                // 2022/10/17
                // * 選択月($strmon)の７($mon)月後を取得
                $annmonth = [intval($this->getbase_submonth($interim_mail, 7 ))];
            }
        }

        //count sqlを取得
        $flg = 1;   // count sql
        $query = $this->ret_query($organization_id, $individual, $interim_mail, $announce_month, $annmonth, $flg);

        $customers = DB::select($query);
        $count     = $customers[0]->count;

        //select sqlを取得
        $flg = 2;   // select sql
        $query = $this->ret_query($organization_id, $individual, $interim_mail, $announce_month, $annmonth, $flg);

        $customers = DB::select($query);

        //--- 2022/11/15 Mail添付 下記を追記
        $postData = $request->all();
        // if (isset($postData['file'])) {
        //     $image     = $postData['file'];
        //     $file_name = $image->getClientOriginalName();         // FileName
        //     Log::info('newsrepo sendmail $count 添付あり = ' . print_r($count, true));
        //     $postData['putFileInfo'] = $this->mailAttachmentService->saveFile($request->all());
        // } else {
        //     $file_name = '';
        //     $postData['putFileInfo'] = '';
        //     Log::info('newsrepo sendmail $count 添付なし = ' . print_r($count, true));
        // }
        //--- 2022/11/15 Mail添付 上記までを追記

        //139件 法人69  個人70
        Log::info('newsrepo sendmail $count = ' . print_r($count, true));

        if($count > 0){
            // 2023/09/30 9/28 TEST用で送信されたので、$toadr   = "system@arkhe-eco.com";は固定。
            // $dbug  = 2;     // 1:Aizen 1以外:arkhe
            // $books = DB::table('books')->first();
            // $dbug  = $books->price;
            // if($dbug == 1){
            //     $toadr   = "y-shintomi@aizen-sol.co.jp";
            // } else {
            //     $toadr   = "system@arkhe-eco.com";
            // }

            $toadr   = "system@arkhe-eco.com";

            //複数宛先をまとめて
            $comment = $request->input('comment');
            $name = "お客様各位";
            $ret_val    = array();
            foreach ($customers as $customers2) {
                // 2022/10/22 値が入っていれば、trueの処理
                if (isset($customers2->email)) {
                    $emails =  $customers2->email.' ';
                    array_push($ret_val, $emails);
                }
            }
            Log::info('newsrepo sendmail $ret_val = ' . print_r($ret_val, true));
            Log::info('newsrepo sendmail $toadr   = ' . print_r($toadr, true));

            Mail::to($toadr)->bcc($ret_val)->send(new MailSend($name, $comment, $postData));

        } else {
            $validator = "送信先がありません。";
            return redirect('newsrepo/create')->withErrors($validator)->withInput();
        }

//debug---
        // $ret_val    = array();
        // $ret_val = [
        //     'shintomi.sh@gmail.com',
        //     'aizenedc@gmail.com',
        //     // 'n.yabu@arkhe-eco.com',
        // ];
        // Log::debug('newsrepo sendmail debug $ret_val = ' . print_r($ret_val, true));

        //宛て先ごと
        // $cnt = 0;
        // foreach ($users as $user) {
        //     // Mail::send(new MailSend($user->name,$user->email,$comment));
        //     $cnt = $cnt +1;
        //     if(env('MAIL_HOST', false) == 'smtp.mailtrap.io'){
        //         sleep(1); //use usleep(500000) for half a second or less
        //     }
        // }
        // Log::debug('newsrepo sendmail $cnt = ' . print_r($cnt, true));
//---debug
        // session()->flash('toastr', config('toastr.mail_success'));

        DB::beginTransaction();
        Log::info('beginTransaction - newsrepo sendmail start');

        try {
            // 2022/12/31
            $query = '';
            $query .= 'UPDATE ';
            $query .= 'newsrepos ';
            $query .= 'SET newsrepos.urgent_flg = 1 ';
            $query .= 'WHERE  ';
            if($individual == 3){
                $query .= 'newsrepos.individual_mail BETWEEN 1 AND 3 ';
            } elseif($individual == 2) {
                $query .= 'newsrepos.individual_mail BETWEEN 2 AND 3 ';
            } elseif($individual == 1) {
                $query .= 'newsrepos.individual_mail != 2  ';
            }
            $query .= 'AND newsrepos.deleted_at is NULL ';
            $query .= 'AND newsrepos.urgent_flg = 2 ';

            DB::update($query);
            // Log::debug('newsrepo store UPDATE $query = ' . $query);

            $newsrepo = new newsrepo();
            $newsrepo->organization_id   = $request->organization_id;
            $newsrepo->comment           = $request->comment;
            $newsrepo->mail_flg          = 1;                           // 1:Mail 2:登録
            $newsrepo->individual_mail   = $request->individual_mail;
            $newsrepo->interim_mail      = $request->interim_mail;
            $newsrepo->announce_month    = $request->announce_month;
            // $newsrepo->file_name         = $file_name;
            $newsrepo->urgent_flg        = 2;   //2022/12/30 至急フラグ(1):通常 (2):至急
            $newsrepo->updated_at        = now();
            $newsrepo->save();           //  Inserts
            DB::commit();
            Log::info('beginTransaction - newsrepo sendmail end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - newsrepo sendmail end(rollback)');
        }

        // Newsreposを取得
        if($organization_id == 0) {
            $newsrepos = Newsrepo::whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('created_at', 'desc')
                        ->paginate(300);
        } else {
            $newsrepos = Newsrepo::where('organization_id','=',$organization_id)
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('created_at', 'desc')
                        ->paginate(300);
        }

        // Log::debug('newsrepo sendmail 法人/個人 $individual     = ' . print_r($individual, true));
        // Log::debug('newsrepo sendmail 選択月    $interim_mail   = ' . print_r($interim_mail, true));
        // Log::debug('newsrepo sendmail 告知月    $announce_month = ' . print_r($announce_month, true));

        $common_no = '02';
        $compacts = compact( 'user_id', 'common_no','newsrepos' ,'organization_id','nowmonth');

        Log::info('newsrepo sendmail END');

        return redirect()->route( 'newsrepo.index', $compacts)->with('message', 'メール送信完了');

    }

    public function temp_serch(Request $request)
    {
        Log::info('newsrepo temp_serch START');

        // ログインユーザーのユーザー情報を取得する
        $user    = $this->auth_user_info();
        $user_id = $user->id;

        // ログインユーザーの組織情報を取得する
        $organization    = $this->auth_user_organization();
        $organization_id = $organization->id;

        // 今月の月を取得
        $nowmonth = intval($this->get_now_month());

        // 法人/個人
        $individual = $request->individual_mail;

        // 選択月
        $interim_mail = $request->interim_mail;

        // 告知月 (1):ー (2):決算月1ケ月前 (3):決算月1ケ月後 (4):決算月2ケ月後 (5):決算月7ケ月後
        $announce_month = $request->announce_month;
        if($announce_month == 1){
            $annmonth = [1,2,3,4,5,6,7,8,9,10,11,12,13];
        } else {
            if($announce_month == 2){
                // * 選択月($strmon)の１($mon)月前を取得
                $annmonth = [intval($this->getbase_specify_month($interim_mail, 1 ))];
            } elseif($announce_month == 3) {
                // * 選択月($strmon)の１($mon)月後を取得
                $annmonth = [intval($this->getbase_submonth($interim_mail, 1 ))];
            } elseif($announce_month == 4) {
                // * 選択月($strmon)の２($mon)月後を取得
                $annmonth = [intval($this->getbase_submonth($interim_mail, 2 ))];
            } elseif($announce_month == 5) {
                // * 選択月($strmon)の７($mon)月後を取得
                $annmonth = [intval($this->getbase_submonth($interim_mail, 7 ))];
            }
        }

        //count sqlを取得
        $flg = 1;   // count sql
        $query = $this->ret_query($organization_id, $individual, $interim_mail, $announce_month, $annmonth, $flg);

        $customers = DB::select($query);
        $count     = $customers[0]->count;

        //select sqlを取得
        $flg = 2;   // select sql
        $query = $this->ret_query($organization_id, $individual, $interim_mail, $announce_month, $annmonth, $flg);

        $customers = DB::select($query);

        //139件 法人69  個人70
        Log::info('newsrepo temp_serch $count = ' . print_r($count, true));

        //コメントを取得
        $comment_out = $this->ret_comment($individual, $interim_mail, $announce_month, $count);

        // Log::debug('newsrepo temp_serch 法人/個人 $individual     = ' . print_r($individual, true));
        // Log::debug('newsrepo temp_serch 選択月    $interim_mail   = ' . print_r($interim_mail, true));
        // Log::debug('newsrepo temp_serch 告知月    $announce_month = ' . print_r($announce_month, true));
        // Log::debug('newsrepo temp_serch $query = ' . print_r($query,true));
        // Log::info(' newsrepo temp_serch $count = ' . print_r($count, true));

        $newsrepos = Newsrepo::where('organization_id','>=',$organization_id)
                    ->whereNull('deleted_at')
                    ->sortable()
                    ->orderBy('created_at', 'desc')
                    ->paginate(300);

        $compacts = compact( 'user_id','organization_id','newsrepos','nowmonth','customers','individual','interim_mail','announce_month','comment_out','count' );

        Log::info('newsrepo temp_serch END');
        return view( 'newsrepo.create', $compacts );
    }

    /**
     *    ret_query()      : Queryを取得 2022/12/30
     *    $organization_id : 組織ID
     *    $individual      : 法人/個人
     *    $interim_mail    : 選択月
     *    $announce_month  : 告知月  (1):ー (2):決算月1ケ月前 (3):決算月1ケ月後 (4):決算月2ケ月後 (5):決算月7ケ月後
     *    $announce        : 決算月
     *    $flg             : 1:select count(*)  2:select *
     */
    public function ret_query($organization_id, $individual, $interim_mail, $announce_month, $annmonth, $flg)
    {
        Log::info('newsrepo ret_query START');

        // 会計未処理
        if($announce_month == 6){
            // 3ヶ月前
            $date = new Carbon(now());
            $old  = $date->subMonths(3);
            $str = ( new DateTime($old))->format('Y-m-d');

            if($flg == 1){
                // count sql
                $query = '';
                $query .= 'select count(*) AS count ';
            } else {
                // select sql
                $query = '';
                $query .= 'select * ';
            }

            $query .= 'from customers ';
            $query .= 'where deleted_at is NULL AND ';
            $query .= 'organization_id >= %organization_id% AND ';
            $query .= '(email != \'\' and email is not NULL) AND ';
            $query .= 'active_cancel <> 3 AND ';
            $query .= 'notificationl_flg = 2 AND ';
            $query .= '(final_accounting_at < %str%  OR final_accounting_at is NULL) AND ';
            if($individual == 3){
                $query .= '(individual_class between 1 AND 2 ) ';
            } elseif($individual == 1) {
                $query .= '(individual_class = 1 ) ';
            } elseif($individual == 2) {
                $query .= '(individual_class = 2 ) ';
            }
            $query     = str_replace('%organization_id%', $organization_id, $query);
            $query     = str_replace('%str%',             $str,             $query);

        } else {

            if($flg == 1){
                // count sql
                $query = '';
                $query .= 'select count(*) AS count ';
            } else {
                // select sql
                $query = '';
                $query .= 'select * ';
            }

            $query .= 'from customers ';
            $query .= 'where deleted_at is NULL AND ';
            $query .= 'organization_id >= %organization_id% AND ';
            $query .= '(email != \'\' and email is not NULL) AND ';
            $query .= 'active_cancel <> 3 AND ';
            $query .= 'notificationl_flg = 2 AND ';

            if($individual == 3){
                $query .= '(individual_class between 1 AND 2 ) AND ';
                if($interim_mail == 13){
                    $query .= '(closing_month between 1 AND 13 ) ';
                } else {
                    if($announce_month == 1){   //告知月
                        $query .= '(closing_month between 1 AND 13 ) ';
                    } else {
                        $query .= '(closing_month = %annmonth% ) ';
                        $query = str_replace_array('%annmonth%', $annmonth,        $query);
                    }
                }
            } elseif($individual == 1) {
                $query .= '(individual_class between 1 AND 1 ) AND ';
                if($interim_mail == 13){
                    $query .= '(closing_month between 1 AND 12 ) ';
                } else {
                    if($announce_month == 13){
                        $query .= '(closing_month between 1 AND 12 ) ';
                    } else {
                        if($announce_month == 1){   //告知月
                            $query .= '(closing_month between 1 AND 13 ) ';
                        } else {
                            $query .= '(closing_month = %annmonth% ) ';
                            $query = str_replace_array('%annmonth%', $annmonth,        $query);
                        }
                    }
                }
            } elseif($individual == 2) {
                $query .= '(individual_class between 2 AND 2 ) AND ';
                $query .= '(closing_month between 13 AND 13 ) ';
            }
            $query = str_replace('%organization_id%', $organization_id, $query);
        }

        Log::info('newsrepo ret_query END');

        return $query;
    }

    /**
     *    ret_comment() コメントを取得 2022/12/30
     *    $individual     : 法人/個人
     *    $interim_mail   : 選択月
     *    $announce_month : 告知月
     *    $count          : データ数
     */
    public function ret_comment($individual, $interim_mail, $announce_month, $count)
    {
        Log::info('newsrepo ret_comment START');

        $comment_out = "";

        // 法人/個人
        if($individual == 3){

        // "法人"
        } elseif($individual == 1) {
            // "－"
            if($announce_month == 1){

            } else {
                // 今月を基準として1か月前が決算月の会社の表示
                if($announce_month == 2){
                    $comment_out = "「決算月１カ月前です。決算の打合せを行いましょう。」";
                }
                // 選択月($strmon)の1月後を取得
                if($announce_month == 3){
                    $comment_out = "「来月が申告月です。納税まで忘れずに行いましょう。」";
                }
                // 選択月($strmon)の2月後を取得
                if($announce_month == 4){
                    $comment_out = "「今月が申告月です。納税まで忘れずに行いましょう。」";
                }
                // 選択月($strmon)の7月後を取得
                if($announce_month == 5){
                    $comment_out = "「予定納税の納付書が届く頃です。支払したら、納付書の画像を送ってください。」";
                }
            }
        } elseif($individual == 2) {

            if($interim_mail == 13){

            } elseif($interim_mail == 1 ) {
                $comment_out = "「今月、住民税の支払い（４回目）があります。"."\n";
                $comment_out = $comment_out."また、2月16日より確定申告開始です。3月15日までに申告と納税が必要です。」";
            } elseif($interim_mail == 2 ) {
                $comment_out = "「2月16日より確定申告開始です。3月15日までに申告と納税が必要です。」";
            } elseif($interim_mail == 3 ) {
                $comment_out = "「3月15日までに申告と納税が必要です。期限に気を付けてください。」";
            } elseif($interim_mail == 5 ) {
                $comment_out = "「住民税の納付書が届く頃です。確認をお願いします。」";
            } elseif($interim_mail == 6 ) {
                $comment_out = "「予定納税の納付書が届く頃です。"."\n";
                $comment_out = $comment_out."　　　　対象は下記の方です。"."\n";
                $comment_out = $comment_out."　　　　①所得税　「前年の所得税が15万円以上の方」"."\n";
                $comment_out = $comment_out."　　　　②消費税　「前年の消費税が60万円以上の方」"."\n";
                $comment_out = $comment_out."　　　　確認をお願いします。」"."\n";
                $comment_out = $comment_out."「今月、住民税の支払い（１回目）があります。」";
            } elseif($interim_mail == 7 ) {
                $comment_out = "「今月、下記を注意してください。"."\n";
                $comment_out = $comment_out."　　　　①予定納税の納付書が届く頃です。"."\n";
                $comment_out = $comment_out."　　　　対象は下記の方です。"."\n";
                $comment_out = $comment_out."　　　　所得税　前年の所得税が290万円以上の方"."\n";
                $comment_out = $comment_out."　　　　②個人事業税の納付書が届く頃です。"."\n";
                $comment_out = $comment_out."　　　　確認をお願いします。」";
            } elseif($interim_mail == 8 ) {
                $comment_out = "「今月、下記を注意してください。"."\n";
                $comment_out = $comment_out."　　　　①住民税の支払い（２回目）があります。"."\n";
                $comment_out = $comment_out."　　　　②予定納税の支払い（消費税）があります。"."\n";
                $comment_out = $comment_out."　　　　③個人事業税の支払い（１回目）があります。」";
            } elseif($interim_mail == 10 ) {
                $comment_out = "「今月、住民税の支払い（３回目）があります。」";
            } elseif($interim_mail == 11 ) {
                $comment_out = "「今月、下記に注意してください。"."\n";
                $comment_out = $comment_out."　　　　①予定納税の支払い（所得税：２回目）があります"."\n";
                $comment_out = $comment_out."　　　　②今月、個人事業税の支払い（２回目）があります。";
            }
        }

        if($announce_month == 6 && $count > 0){
            $comment_out = "「３か月以上会計データが提出されてません。会計データを提出してください」";
        }

        Log::info('newsrepo ret_comment END');

        return $comment_out;
    }

    public function non_serch(Request $request)
    {
        Log::info('newsrepo non_serch START');


        Log::info('newsrepo non_serch END');

    }


}
