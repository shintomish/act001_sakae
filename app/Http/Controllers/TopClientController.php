<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Newsrepo;
use App\Models\Customer;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class TopClientController extends Controller
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
        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $u_id = $user->id;
        $organization_id =  $user->organization_id;

        Log::info('topclient index START $user->name = ' . print_r($user->name ,true));

        //FileNameは「latestinformation.pdf」固定 2022/09/24
        $books = DB::table('books')->first();
        $str   = ( new DateTime($books->info_date))->format('Y-m-d');
        $latestinfodate = '最新情報'.'('.$str.')';
            // Log::debug('topclient index latestinfodate  = ' . print_r($latestinfodate ,true));

        // Customer(複数レコード)情報を取得する
        $customer_findrec = $this->auth_customer_findrec();
        $customer_id = $customer_findrec[0]['id'];
            // Log::debug('topclient index customer_id  = ' . print_r($customer_id ,true));

        // 2022/11/10
        $indiv_class = $customer_findrec[0]['individual_class'];
            // Log::debug('topclient index customer_id  = ' . print_r($customer_id ,true));

        // 2022/08/25
        // 例：㈱リアライズ（６月決算）
        // ①　５月になったら「決算月１カ月前です」
        // ②　８月になったら「今月が申告月です。納税まで忘れずに行いましょう」
        // ③　１月（中間納付月＝1月　の数字を参照）になったら「予定納税の納付書が届く頃です。支払したら、納付書の画像を送ってください」
        // ④　（法人の場合individual_class）納期の特例「提出済み」の場合、６月と12月になると各事業主の画面に「来月、所得税の納付あります」
        // 2022/08/30
        // 追加で
        // ④決算月の１か月後は「来月が申告月です。納税まで忘れずに行いましょう」
        // ⑤会計データを３か月以上提出していないお客様に「会計データを提出してください」
        $notice_0 = 1;
        $notice_1 = 1;  // ①決算月の数字を参照し、決算月１カ月前になると画面に「決算月１カ月前です」のように表示
        $notice_2 = 1;  // ②中間納付月の数字を参照し、７カ月後になると画面に「中間納付の納付書が届く時期です」と表示
                        // ②決算月の数字を参照し、決算月１カ月前になると画面に「予定納税の納付書が届く頃です。
                        // 支払したら、納付書の画像を送ってください」と表示
        $notice_3 = 1;  // ③納期の特例「提出済み」の場合、６月と12月になると画面に「来月、所得税の納付があります」と表示
        $notice_4 = 1;  // 会計データをアップロードしないで３カ月以上過ぎた場合、「会計データが最近提出されてません」と表示
        $notice_5 = 1;  // ②決算月の数字を参照し、決算月２カ月後になると「今月が申告月です。納税まで忘れずに行いましょう」と表示
        $notice_6 = 1;  // ③決算月の数字を参照し、決算月１カ月後になると「来月が申告月です。納税まで忘れずに行いましょう」と表示
        $notice_7 = 1;  // ⑥決算月の数字を参照し、決算月当月になると「今月が決算月です。申告相談の連絡を待ってます」と表示
        $notice_8 = 1;  // 11月：「来月、年末調整を行います。期限までに必要資料をアップロードしてください」と表示
        $notice_9 = 1;  // 12月：「年末調整の時期です。早急にアップロードをしてください」と表示

        // 2022/09/11
        // こちら個人事業主のお客様にもお願いできますか？
        $notice_11 = 1; // １月：「今月、住民税の支払い（４回目）があります」
                        //     ：「2月16日より確定申告開始です。3月15日までに申告と納税が必要です」
        $notice_12 = 1; // ２月：「2月16日より確定申告開始です。3月15日までに申告と納税が必要です」
        $notice_13 = 1; // ３月：「3月15日までに申告と納税が必要です」
        $notice_15 = 1; // ５月：「住民税の納付書が届く頃です。確認をお願いします」
        $notice_16 = 1; // ６月：「予定納税の納付書が届く頃です。
                        //        対象は下記の方です。
                        //        ①所得税 「前年の所得税が15万円以上の方」
                        //        ②消費税 「前年の消費税が60万円以上の方」
                        //        確認をお願いします」
                        //       「今月、住民税の支払い（１回目）があります」
        $notice_17 = 1; // ７月：「今月、予定納税の納付書が届く頃です。
                        //        対象は下記の方です。
                        //        所得税 「前年の所得税が290万円以上の方」
                        //        確認をお願いします」
                        // ：    「今月、個人事業税の支払い（所得税：１回目）があります」
        $notice_18 = 1; // ８月：「今月、住民税の支払い（２回目）があります」
                        //     ：「今月、予定納税の支払い（消費税）があります」
                        //     ：「今月、個人事業税の支払い（１回目）があります」
        $notice_20 = 1; // 10月：「今月、住民税の支払い（３回目）があります」
        $notice_21 = 1; // 11月：「今月、予定納税の支払い（所得税：２回目）があります」
                        //     ：「今月、個人事業税の支払い（２回目）があります」

        // 今月の月を取得
        $nowmonth = intval($this->get_now_month());

        // debug
        // $nowmonth = 1;

        foreach ($customer_findrec as $costomer2) {
            // Log::debug('topclient index $costomer2[id]  = ' . print_r($costomer2['id'] ,true));
            // Log::debug('topclient index $costomer2[closing_month]  = ' . print_r($costomer2['closing_month'] ,true));

            if($costomer2['id'] == $customer_id) {

                // 通知しない(1):通知する(2)
                if ($costomer2['notificationl_flg'] == 2) {
                    $notice_0 = 2;
                    // 2022/08/30
                    // * 基準月($strmon)の〇($mon)月前を取得
                    $submonth1 = intval($this->getbase_submonth($costomer2['closing_month'], 1 ));

                    // * 基準月($strmon)の１($mon)月後を取得
                    $addmonth1 = intval($this->getbase_specify_month($costomer2['closing_month'], 1 ));

                    // * 基準月($strmon)の２($mon)月後を取得
                    $addmonth2 = intval($this->getbase_specify_month($costomer2['closing_month'], 2 ));

                    // * 基準月($strmon)の７($mon)月後を取得
                    $addmonth7 = intval($this->getbase_specify_month($costomer2['closing_month'], 7 ));

                    // * 決算月を取得
                    $closmonth = intval($this->get_closing_month($costomer2['closing_month']));

                    // Log::debug('topclient index $closing_month  = ' . print_r($costomer2['closing_month'] ,true));
                    // Log::debug('topclient index $nowmonth  = ' . print_r($nowmonth ,true));
                    // Log::debug('topclient index $submonth1  = ' . print_r($submonth1 ,true));
                    // Log::debug('topclient index $addmonth1  = ' . print_r($addmonth1 ,true));
                    // Log::debug('topclient index $addmonth2  = ' . print_r($addmonth2 ,true));
                    // Log::debug('topclient index $addmonth7  = ' . print_r($addmonth7 ,true));

                    if($submonth1 == $nowmonth) {   // 決算月 の1月前
                        $notice_1 = 2;
                    }

                    if($addmonth1 == $nowmonth) {   // ②決算月、決算月１カ月後
                        $notice_6 = 2;
                    }
                    if($addmonth2 == $nowmonth) {   // ②決算月、決算月２カ月後
                        $notice_5 = 2;
                    }
                    if($closmonth == $nowmonth) {   // ⑥決算月、決算月当月
                        $notice_7 = 2;
                    }

                    // 2022/08/30
                    // 中間納付 [1:1月～12:12月 13:なし]から決算月７か月後に変更
                    // if ($costomer2['interim_payment'] == 13 ) {
                    if($addmonth7 == $nowmonth) {   // ②決算月の数字を参照し、決算月２カ月後
                        $notice_2 = 2;
                    }

                    // 2022/11/10
                    // $individual_class = 3;

                    // 2022/08/25
                    // ④　（法人の場合）'法人(1):個人事業主(2)'
                    if ($costomer2['individual_class']  == 1 ) {
                        $individual_class = 1;      //2022/11/10
                        // 納期の特例 1:未提出 2:提出済み ６月と12月
                        if ($costomer2['special_delivery_date']  == 2 ) {
                            if( $nowmonth == 6 || $nowmonth == 12 ) {
                                $notice_3 = 2;
                            }
                        }
                        // 2022/09/11
                        // 11月：「来月、年末調整を行います
                        if( $nowmonth == 11 ) {
                            $notice_8 = 2;
                        }
                        // 12月：「年末調整の時期です。
                        if( $nowmonth == 12 ) {
                            $notice_9 = 2;
                        }
                    }

                    // 2022/09/11
                    // ④（個人事業主の場合）'法人(1):個人事業主(2)'
                    if ($costomer2['individual_class']  == 2 ) {
                        $individual_class = 2;      //2022/11/10
                        if( $nowmonth == 1 ) {
                            $notice_11 = 2;
                        }
                        if( $nowmonth == 2 ) {
                            $notice_12 = 2;
                        }
                        if( $nowmonth == 3 ) {
                            $notice_13 = 2;
                        }
                        if( $nowmonth == 5 ) {
                            $notice_15 = 2;
                        }
                        if( $nowmonth == 6 ) {
                            $notice_16 = 2;
                        }
                        if( $nowmonth == 7 ) {
                            $notice_17 = 2;
                        }
                        if( $nowmonth == 8 ) {
                            $notice_18 = 2;
                        }
                        if( $nowmonth == 10 ) {
                            $notice_20 = 2;
                        }
                        if( $nowmonth == 11 ) {
                            $notice_21 = 2;
                        }
                    }

                    $flg = "0";
                    //  会計データをアップロードしないで３カ月以上過ぎた場合(1) 過ぎてない(0)
                    $flg = $this->get_three_month($costomer2['id']);

                    if( $flg == "1" ) {
                        $notice_4 = 2;
                    }

                //2022/12/27
                } else {
                    $individual_class = $costomer2['individual_class'];
                }
            }
        }

        // individual_mail 法人(1):個人(2):全て(3)
        if($organization_id == 0) {
            $newsrepos = Newsrepo::whereNull('deleted_at')
                        // 2022/11/10
                        ->where('individual_mail',3)
                        ->orwhere('individual_mail',$individual_class)
                        ->sortable()
                        ->orderBy('created_at', 'desc')
                        ->paginate(2);
        } else {
            $newsrepos = Newsrepo::where('organization_id','=',$organization_id)
                        // 2022/11/10
                        ->where('individual_mail',3)
                        ->orwhere('individual_mail',$individual_class)
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('created_at', 'desc')
                        ->paginate(2);
        }
        $jsonfile = storage_path() . "/tmp/customer_info_status_". $customer_id. ".json";
        // $jsonfile = storage_path() . "/customer_info_status.json";
            // Log::debug('topclient index $jsonfile  = ' . print_r($jsonfile ,true));

        $compacts = compact( 'indiv_class','notice_0','notice_1','notice_2','notice_3','notice_4','notice_5','notice_6', 'notice_7', 'notice_8', 'notice_9',  'notice_11','notice_12',
        'notice_13','notice_15','notice_16','notice_17', 'notice_18',
        'notice_20','notice_21',
        'newsrepos',
        'customer_findrec','customer_id','jsonfile','latestinfodate' );

        Log::info('topclient index END $user->name = ' . print_r($user->name ,true));
        // Log::info('topclient index END');
        return view( 'topclient.index', $compacts );

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serch(Request $request)
    {
        Log::info('topclient serch START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $customer_id = $request->Input('customer_id');

        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $u_id = $user->id;
        $organization_id =  $user->organization_id;

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

        $notice_0 = 1;
        $notice_1 = 1;  // ①決算月の数字を参照し、決算月１カ月前になると画面に「決算月１カ月前です」のように表示
        $notice_2 = 1;  // ②中間納付月の数字を参照し、７カ月後になると画面に「予定納税の納付書が届く頃です。支払したら、納付書の画像を送ってください」と表示
                        // ②決算月の数字を参照し、決算月１カ月前になると画面に「予定納税の納付書が届く頃です。
                        // 支払したら、納付書の画像を送ってください」と表示
        $notice_3 = 1;  // ③納期の特例「提出済み」の場合、６月と12月になると画面に「来月、所得税の納付があります」と表示
        $notice_4 = 1;  // 会計データをアップロードしないで３カ月以上過ぎた場合、「会計データが最近提出されてません」と表示
        $notice_5 = 1;  // ②決算月の数字を参照し、決算月２カ月後になると「今月が申告月です。納税まで忘れずに行いましょう」と表示
        $notice_6 = 1;  // ③決算月の数字を参照し、決算月１カ月後になると「来月が申告月です。納税まで忘れずに行いましょう」と表示
        $notice_7 = 1;  // ⑥決算月の数字を参照し、決算月当月になると「今月が決算月です。申告相談の連絡を待ってます」と表示
        $notice_8 = 1;  // 11月：「来月、年末調整を行います。期限までに必要資料をアップロードしてください」と表示
        $notice_9 = 1;  // 12月：「年末調整の時期です。早急にアップロードをしてください」と表示

        // 2022/09/11
        // こちら個人事業主のお客様にもお願いできますか？
        $notice_11 = 1; // １月：「今月、住民税の支払い（４回目）があります」
                        //     ：「2月16日より確定申告開始です。3月15日までに申告と納税が必要です」
        $notice_12 = 1; // ２月：「2月16日より確定申告開始です。3月15日までに申告と納税が必要です」
        $notice_13 = 1; // ３月：「3月15日までに申告と納税が必要です」
        $notice_15 = 1; // ５月：「住民税の納付書が届く頃です。確認をお願いします」
        $notice_16 = 1; // ６月：「予定納税の納付書が届く頃です。
                        //        対象は下記の方です。
                        //        ①所得税 「前年の所得税が15万円以上の方」
                        //        ②消費税 「前年の消費税が60万円以上の方」
                        //        確認をお願いします」
                        //       「今月、住民税の支払い（１回目）があります」
        $notice_17 = 1; // ７月：「今月、予定納税の納付書が届く頃です。
                        //        対象は下記の方です。
                        //        所得税 「前年の所得税が290万円以上の方」
                        //        確認をお願いします」
                        // ：    「今月、個人事業税の支払い（所得税：１回目）があります」
        $notice_18 = 1; // ８月：「今月、住民税の支払い（２回目）があります」
                        //     ：「今月、予定納税の支払い（消費税）があります」
                        //     ：「今月、個人事業税の支払い（１回目）があります」　
        $notice_20 = 1; // 10月：「今月、住民税の支払い（３回目）があります」
        $notice_21 = 1; // 11月：「今月、予定納税の支払い（所得税：２回目）があります」
                        //     ：「今月、個人事業税の支払い（２回目）があります」

        // 今月の月を取得
        $nowmonth = intval($this->get_now_month());

        // debug
        // $nowmonth = 4;

        foreach ($customer_findrec as $costomer2) {
            // Log::debug('topclient index $costomer2[id]  = ' . print_r($costomer2['id'] ,true));
            // Log::debug('topclient index $costomer2[closing_month]  = ' . print_r($costomer2['closing_month'] ,true));

            if($costomer2['id'] == $customer_id) {

                // 通知しない(1):通知する(2)
                if ($costomer2['notificationl_flg'] == 2) {
                    $notice_0 = 2;
                    // 2022/08/30
                    // * 基準月($strmon)の〇($mon)月前を取得
                    $submonth1 = intval($this->getbase_submonth($costomer2['closing_month'], 1 ));

                    // * 基準月($strmon)の１($mon)月後を取得
                    $addmonth1 = intval($this->getbase_specify_month($costomer2['closing_month'], 1 ));

                    // * 基準月($strmon)の２($mon)月後を取得
                    $addmonth2 = intval($this->getbase_specify_month($costomer2['closing_month'], 2 ));

                    // * 基準月($strmon)の７($mon)月後を取得
                    $addmonth7 = intval($this->getbase_specify_month($costomer2['closing_month'], 7 ));

                    // * 決算月を取得
                    $closmonth = intval($this->get_closing_month($costomer2['closing_month']));

                    // Log::debug('topclient serch $closing_month  = ' . print_r($costomer2['closing_month'] ,true));
                    // Log::debug('topclient serch $nowmonth  = ' . print_r($nowmonth ,true));
                    // Log::debug('topclient serch $submonth1  = ' . print_r($submonth1 ,true));
                    // Log::debug('topclient serch $addmonth1  = ' . print_r($addmonth1 ,true));
                    // Log::debug('topclient serch $addmonth2  = ' . print_r($addmonth2 ,true));
                    // Log::debug('topclient serch $addmonth7  = ' . print_r($addmonth7 ,true));

                    if($submonth1 == $nowmonth) {   // 決算月 の1月前
                        $notice_1 = 2;
                    }

                    if($addmonth1 == $nowmonth) {   // ②決算月、決算月１カ月後
                        $notice_6 = 2;
                    }
                    if($addmonth2 == $nowmonth) {   // ②決算月、決算月２カ月後
                        $notice_5 = 2;
                    }
                    if($closmonth == $nowmonth) {   // ⑥決算月、決算月当月
                        $notice_7 = 2;
                    }

                    // 2022/08/30
                    // 中間納付 [1:1月～12:12月 13:なし]から決算月７か月後に変更
                    // if ($costomer2['interim_payment'] == 13 ) {
                    if($addmonth7 == $nowmonth) {   // ②決算月の数字を参照し、決算月２カ月後
                        $notice_2 = 2;
                    }

                    // 2022/08/25
                    // ④（法人の場合）'法人(1):個人事業主(2)'
                    if ($costomer2['individual_class']  == 1 ) {
                        $individual_class = 1;
                        // 納期の特例 1:未提出 2:提出済み ６月と12月
                        if ($costomer2['special_delivery_date']  == 2 ) {
                            if( $nowmonth == 6 || $nowmonth == 12 ) {
                                $notice_3 = 2;
                            }
                        }
                        // 2022/09/11
                        // 11月：「来月、年末調整を行います
                        if( $nowmonth == 11 ) {
                            $notice_8 = 2;
                        }
                        // 12月：「年末調整の時期です。
                        if( $nowmonth == 12 ) {
                            $notice_9 = 2;
                        }
                    }

                    // 2022/09/11
                    // ④（個人事業主の場合）'法人(1):個人事業主(2)'
                    if ($costomer2['individual_class']  == 2 ) {
                        $individual_class = 2;
                        if( $nowmonth == 1 ) {
                            $notice_11 = 2;
                        }
                        if( $nowmonth == 2 ) {
                            $notice_12 = 2;
                        }
                        if( $nowmonth == 3 ) {
                            $notice_13 = 2;
                        }
                        if( $nowmonth == 5 ) {
                            $notice_15 = 2;
                        }
                        if( $nowmonth == 6 ) {
                            $notice_16 = 2;
                        }
                        if( $nowmonth == 7 ) {
                            $notice_17 = 2;
                        }
                        if( $nowmonth == 8 ) {
                            $notice_18 = 2;
                        }
                        if( $nowmonth == 10 ) {
                            $notice_20 = 2;
                        }
                        if( $nowmonth == 11 ) {
                            $notice_21 = 2;
                        }
                    }

                    $flg = "0";
                    //  会計データをアップロードしないで３カ月以上過ぎた場合(1) 過ぎてない(0)
                    $flg = $this->get_three_month($costomer2['id']);

                    if( $flg == "1" ) {
                        $notice_4 = 2;
                    }

                //2022/12/27
                } else {
                    $individual_class = $costomer2['individual_class'];
                }
            }
        }

        // Log::debug('topclient serch $notice_3  = ' . print_r($notice_3 ,true));
        if($organization_id == 0) {
            $newsrepos = Newsrepo::whereNull('deleted_at')
                        // 2022/11/10
                        ->where('individual_mail',3)
                        ->orwhere('individual_mail',$individual_class)
                        ->sortable()
                        ->orderBy('created_at', 'desc')
                        ->paginate(2);
        } else {
            $newsrepos = Newsrepo::where('organization_id','=',$organization_id)
                        // 2022/11/10
                        ->where('individual_mail',3)
                        ->orwhere('individual_mail',$individual_class)
                        ->whereNull('deleted_at')
                        ->sortable()
                        ->orderBy('created_at', 'desc')
                        ->paginate(2);
        }

        //FileNameは「latestinformation.pdf」固定 2022/09/24
        $books = DB::table('books')->first();
        $str   = ( new DateTime($books->info_date))->format('Y-m-d');
        $latestinfodate = '最新情報'.'('.$str.')';

        $jsonfile = storage_path() . "/tmp/customer_info_status_". $customer_id. ".json";
        // $jsonfile = storage_path() . "/customer_info_status.json";
            // Log::debug('topclient serch $jsonfile  = ' . print_r($jsonfile ,true));

        $compacts = compact( 'indiv_class','notice_0','notice_1','notice_2','notice_3','notice_4','notice_5','notice_6', 'notice_7', 'notice_8', 'notice_9',  'notice_11','notice_12',
        'notice_13','notice_15','notice_16','notice_17', 'notice_18',
        'notice_20','notice_21',
        'newsrepos',
        'customer_findrec','customer_id','jsonfile','latestinfodate' );

        Log::info('topclient serch END');
        return view( 'topclient.index', $compacts );

    }

    public function post(Request $data)
    {
        // Log::info('top post START');
        // Log::info('top post END');
        // // ホーム画面へリダイレクト
        // return redirect('/user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        Log::info('topclient show START');

        $disk = 'local';  // or 's3'
        $storage = Storage::disk($disk);
        $file_name = 'user_manual.pdf';
        $pdf_path = 'public/pdf/' . $file_name;
        $file = $storage->get($pdf_path);

        Log::info('topclient show END');

        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $file_name . '"');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_houjin()
    {
        Log::info('topclient show_houjin START');

        $disk = 'local';  // or 's3'
        $storage = Storage::disk($disk);
        // $file_name = 'latestinformation.pdf';    //FileNameは「latestinformation.pdf」固定
        $file_name = '2022年_年末調整資料.zip';             //2022/11/10 books [info_date]
        $pdf_path = 'public/pdf/' . $file_name;
        $file = $storage->get($pdf_path);

        Log::info('topclient show_houjin END');

        return response($file, 200)
            // ->header('Content-Type', 'application/pdf')
            ->header('Content-Type', 'application/zip')     //2022/11/10
            ->header('Content-Disposition', 'inline; filename="' . $file_name . '"');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_new()
    {
        Log::info('topclient show_new START');

        $disk = 'local';  // or 's3'
        $storage = Storage::disk($disk);
        $file_name = 'latestinformation.pdf';    //FileNameは「latestinformation.pdf」固定
        $pdf_path = 'public/pdf/' . $file_name;
        $file = $storage->get($pdf_path);

        Log::info('topclient show_new END');

        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $file_name . '"');

    }
    // 2022/12/30
    public function show_alert($alert_id)
    {
        Log::info('topclient show_alert START');

        // Log::debug('topclient show_alert $id  = ' . print_r($alert_id ,true));

        $newsrepo = Newsrepo::find($alert_id);

        Log::info('topclient show_alert END');

        return view('components.alert', [
            'comment' => $newsrepo->comment,
        ]);
    }

    // 2023/08/20
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_up01()
    {
        Log::info('topclient show_up01 インボイス START');

        $disk = 'local';  // or 's3'
        $storage = Storage::disk($disk);
        $file_name = 'インボイス制度開始にあたってやるべきこと.pdf';
        $pdf_path = 'public/pdf/' . $file_name;
        $file = $storage->get($pdf_path);

        Log::info('topclient show_up01 インボイス END');

        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            // ->header('Content-Type', 'application/zip')
            ->header('Content-Disposition', 'inline; filename="' . $file_name . '"');

    }

    // 2023/08/20
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_up02()
    {
        Log::info('topclient show_up02 電子帳簿保存法 START');

        $disk = 'local';  // or 's3'
        $storage = Storage::disk($disk);
        $file_name = '改正電子帳簿保存法の開始にあたってやるべきこと.pdf';
        $pdf_path = 'public/pdf/' . $file_name;
        $file = $storage->get($pdf_path);

        Log::info('topclient show_up02 電子帳簿保存法 END');

        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            // ->header('Content-Type', 'application/zip')
            ->header('Content-Disposition', 'inline; filename="' . $file_name . '"');

    }
    // 2023/08/30
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_up03()
    {
        Log::info('topclient show_up03 法人設立 START');

        $disk = 'local';  // or 's3'
        $storage = Storage::disk($disk);
        $file_name = '法人設立・法人成したタイミングで知っておくべき知識.pdf';
        $pdf_path = 'public/pdf/' . $file_name;
        $file = $storage->get($pdf_path);

        Log::info('topclient show_up03 法人設立 END');

        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            // ->header('Content-Type', 'application/zip')
            ->header('Content-Disposition', 'inline; filename="' . $file_name . '"');

    }


}
