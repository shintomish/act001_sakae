<?php

namespace App\Http\Controllers;

use Log;
use DateTime;
use App\Models\Organization;
use App\Models\User;
use App\Models\Book;
use App\Models\UploadUser;
use App\Models\Customer;
use App\Models\ControlUser;
use App\Models\ImageUpload;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //--------------------------------------------------------------------------------------------------
    //-- システム関連
    //--------------------------------------------------------------------------------------------------

    /**
     * ログインユーザーのユーザー情報Userを取得する
     */
    public function auth_user_info()
    {
        Log::info('auth_user_info START');

        $id = auth::user()->id;
        $ret_val = User::find($id);

        // Log::debug('auth_user_info ret_val = ' . print_r(json_decode($ret_val),true));
        Log::info('auth_user_info END');
        return $ret_val;
    }

    /**
     * 選択された顧客IDからCustomer情報(フォルダー名)を取得する
     */
    public function auth_user_foldername($u_id)
    {
        Log::info('auth_user_foldername START');

        // $id = auth::user()->id;
        // $user = User::find($id);
        // $u_id = $user->user_id;

        $ret_val = Customer::where('id',$u_id)->first();

        // Log::debug('auth_user_foldername Customer ret_val = ' . print_r(json_decode($ret_val),true));
        Log::info('auth_user_foldername END');
        return $ret_val;
    }

    /**
     * Customer情報を取得する
     */
    public function auth_customer_all()
    {
        Log::info('auth_customer_all START');

        $id = auth::user()->id;
        $user = User::find($id);

        $o_id = $user->organization_id;

        if($o_id == 0 ){
            $ret_val = Customer::all();
        }else{
            $ret_val = Customer::where('organization_id',$o_id)->get();
        }
// var_dump($ret_val);
// die;
        // Log::debug('auth_customer_all ret_val = ' . print_r(json_decode($ret_val),true));
        Log::info('auth_customer_all END');
        return $ret_val;
    }

    /**
     * Customer(複数レコード)情報を取得するControlUser
     */
    public function auth_customer_findrec()
    {
        Log::info('auth_customer_findrec START');

        $u_id = auth::user()->id;

        Log::info('auth_customer_findrec START $u_id = ' . print_r($u_id ,true));
        if($u_id == 10) {
            $ret_val = Customer::whereNull('deleted_at')
                            // `active_cancel` 1:契約 2:SPOT 3:解約',
                            ->where('active_cancel','!=', 3)
                            ->orderBy('customers.business_name', 'asc')
                            ->get();
            Log::info('auth_customer_findrec END $u_id = ' . print_r($u_id ,true));
            Log::info('auth_customer_findrec END');
            return $ret_val;
        }

        $controlusers = ControlUser::where('user_id',$u_id)
            ->whereNull('deleted_at')
            ->get();
        // Log::debug('auth_customer_findrec count = ' . print_r($controlusers->count(),true));
        if($controlusers->count() > 0) {
            $ret_val = array();
            foreach ($controlusers as $controlusers2) {

                $customers = Customer::where('id',$controlusers2->customer_id)
                    ->orderBy('id', 'asc')
                    ->first();
                array_push($ret_val, $customers );
            }
        } else {
            // 利用者を顧客に変更し複数法人が設定されていないときアルケーエコにし複数法人に登録する
            $ret_val = array();
            $customers = Customer::where('id',1)
                ->orderBy('id', 'asc')
                ->first();
            array_push($ret_val, $customers );

            $conusers = new ControlUser();
            $conusers->organization_id = $customers->organization_id;
            $conusers->user_id         = $u_id;
            $conusers->customer_id     = 1;
            $conusers->save();               //  Inserts
        }
        // Log::debug('auth_customer_findrec ret_val = ' . print_r($ret_val,true));
        Log::info('auth_customer_findrec END $u_id = ' . print_r($u_id ,true));
        Log::info('auth_customer_findrec END');
        return $ret_val;
    }

    /**
     * ログインユーザーの組織IDを取得する
     */
    public function auth_user_organization()
    {
        Log::info('auth_user_organization START');

        $organization_id = auth::user()->organization_id;
        $ret_val = Organization::find($organization_id);

        // Log::debug('auth_user_organization ret_val = ' . print_r(json_decode($ret_val),true));
        Log::info('auth_user_organization END');
        return $ret_val;
    }

    /**
     * ログインユーザーの組織オブジェクトを取得する
     */
    public function auth_user_organization_id()
    {
        Log::info('auth_user_organization_id START');

        $ret_val = auth::user()->organization_id;
        // Log::debug('auth_user_organization_id ret_val = ' . $ret_val);

        Log::info('auth_user_organization_id END');
        return $ret_val;
    }
    /**
    * 当月から加算された月を取得;
    * @return string
    */
    public function get_specify_month($mon): string
    {
        $date = Carbon::parse('now');

        return DATE_FORMAT($date->addMonth($mon),'m'); // $monヶ月後;
    }

    /**
    * 今月の1月前を取得 date("Y-m-d", strtotime("-1 month"));
    * @return string
    */
    public function get_sub_month(): string
    {
        // $date = Carbon::parse('now');
        // $date->subMonth();
        // 1ヶ月前
        $date = Carbon::parse('now');

        return DATE_FORMAT($date->subMonth(),'m');
    }
    /**
    * 今月の〇月前を取得
    * @return string
    */
    public function get_submonth($mon): string
    {
        $date = Carbon::parse('now');

        return DATE_FORMAT($date->subMonth($mon),'m');
    }

    /**
    * 決算月を取得
    * @return string
    */
    public function get_closing_month($strmon): string
    {
        if($strmon == '13') {
            $strmon = 12;
        }

        return $strmon;
    }
    /**
    * 基準月($strmon)の〇($mon)月前を取得
    * @return string
    */
    public function getbase_submonth($strmon, $mon): string
    {
        $date = Carbon::parse('now');
        $stryear = $date->year;
        $strday = 1;
        if($strmon == '13') {
            $strmon = 12;
        }
        $strbase = $stryear .'-'.$strmon.'-'.$strday;

        $datebase = Carbon::parse($strbase);

        return DATE_FORMAT($datebase->subMonth($mon),'m');
    }

    /**
    * 基準月($strmon)の〇($mon)月後を取得;
    * @return string
    */
    public function getbase_specify_month($strmon, $mon): string
    {
        $date = Carbon::parse('now');
        $stryear = $date->year;
        $strday = 1;
        if($strmon == '13') {
            $strmon = 12;
        }
        $strbase = $stryear .'-'.$strmon.'-'.$strday;

        $datebase = Carbon::parse($strbase);

        return DATE_FORMAT($datebase->addMonth($mon),'m'); // $monヶ月後;
    }

    /**
    * 今月の1月後を取得 date("Y-m-d", strtotime("1 month"));
    * @return string
    */
    public function get_add_month(): string
    {
        // $date = Carbon::parse('now');
        // $date->subMonth();
        // 1ヶ月前
        $date = Carbon::parse('now');

        return DATE_FORMAT($date->addMonth(1),'m');
    }
    /**
    * 今月の月を取得
    * @return string
    */
    public function get_now_month(): string
    {
        return DATE_FORMAT(Carbon::now(),'m');
    }
    /**
    * 今年の年を取得
    * @return string
    */
    public function get_now_year(): string
    {
        return DATE_FORMAT(Carbon::now(),'Y');
    }
    /**
    * 今年の年を取得2 Book
    * @return string
    */
    public function get_now_year2(): string
    {
        // $id = 1;
        // $ret_val = Book::find($id);
        // return $ret_val->nowyear;
        // Jsonに変更 2021/12/23 一旦作成
        // $jsonfile = storage_path() . "/app/userdata/year_info.json";
        // $year = 2022;
        // $status = false;
        // $arr = array(
        //     "res" => array(
        //         "info" => array(
        //             [
        //                 "year"       => $year,
        //                 "status"     => $status
        //             ]
        //         )
        //     )
        // );
        // $arr = json_encode($arr);
        // file_put_contents($jsonfile , $arr);
        // --------------------------------------

        // -----Jsonより取得  2021/12/23 -------
        $jsonfile = storage_path() . "/app/userdata/year_info.json";
        $jsonUrl = $jsonfile; //JSONファイルの場所とファイル名を記述
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $obj = json_decode($json, true);
            $obj = $obj["res"]["info"];
            foreach($obj as $key => $val) {
                $year   = $val["year"];
                $status = $val["status"];
            }
            // Log::info('client postUpload  jsonUrl OK');
        } else {
            $year = $this->get_now_year();
            // echo "データがありません";
            // Log::info('client postUpload  jsonUrl NG');
        }

        return $year;
    }

    /**
     * ImageUpload(1レコード)情報を取得する
     * 事業主が会計データをアップロードしないで３カ月以上過ぎた場合(1) 過ぎてない(0)
     */
    public function get_three_month($cus_id): string
    {
        Log::info('get_three_month START');

        // $u_id = auth::user()->id;
        // $user = User::find($id);
        // $u_id = $user->id;

        // $imageUpload = ImageUpload::where('id',$u_id)
        //             ->orderBy('created_at', 'desc')
        //             ->get();

        $ret_val = DB::table('imageuploads')
                    ->where('customer_id',$cus_id)
                    ->orderBy('updated_at', 'desc')
                    ->first();

        $str = "0";
        if (isset($ret_val)) {
            // $str = ( new DateTime($ret_val->created_at))->format('Y-m-d');
            // 3ヶ月前
            $date = new Carbon(now());
            $old = $date->subMonths(3);

            $latest = new Carbon($ret_val->updated_at);

            //未満
            iF($latest->lt($old)) {
                $str = "1";
            }
        }
        // Log::debug('auth_customer_findrec ret_val = ' . print_r(json_decode($ret_val),true));
        Log::info('get_three_month END');
        return $str;
    }
    /**
     * Convert bytes to more appropriate format e.g. MB,GB..
     * @param int $size
     * @return string
     */
    function convertfilesize($insize): string
    {
        if ($insize >= 1073741824) {
            $fileSize = round($insize / 1024 / 1024 / 1024,1) . ' GB';
        } elseif ($insize >= 1048576) {
            $fileSize = round($insize / 1024 / 1024,1) . ' MB';
        } elseif ($insize >= 1024) {
            $fileSize = round($insize / 1024,1) . ' KB';
        } else {
            $fileSize = $insize . ' bytes';
        }
        return $fileSize;
    }
    /**
     *
     */
    function to_string( $time, $format='H:i' )
    {
        Log::info('to_string START');

        if( is_null($time)  ) return null;

        $datetime = new DateTime('2001-01-01 ' . $time);

        Log::debug('datetime = ' . print_r($datetime,true));
        Log::debug('datetime = ' . print_r($datetime,true));

        Log::info('to_string END');
        return $datetime->format($format);
    }

    /**
     *
     */
    function to_time_format( $sec, $format='%02d:%02d:%02d' )
    {
        Log::info('to_time_format START');

        if( is_null($sec)  ) return null;
        Log::debug('$sec = ' . print_r($sec,true));

        $hours = floor( $sec / 3600 );
        $minutes = floor( ( $sec / 60 ) % 60 );
        $seconds = $sec % 60;
        $time_foramt = sprintf($format, $hours, $minutes, $seconds);

        Log::info('to_time_format END');
        return $time_foramt;
    }

    function seconds2hours( $sec )
    {
        if( is_null($sec)  ) return null;

        $hours   = floor( $sec / 3600 );
        $minutes = floor( ( $sec / 60 ) % 60 );
        $time    = $hours + $minutes / 60;

        return $time;
    }

    /**
     * 本日日付の年度を返す
     */
    public function get_fiscal_year($year = null, $month = null)
    {
        Log::info('get_fiscal_year START');

        if( is_null($year) || is_null($month) ){
            $date  = new DateTime();
            $year  = $date->format('Y');
            $month = $date->format('n');
        }

        $f_year = $year;
        if( 1<= $month && $month <= 3){
            // 1~3月の場合は前年となる
            $f_year = $f_year - 1;
        }

        Log::debug('[input year] = ' . $year . ' [input month] = ' . $month . ' [fiscal year] = ' . $year);
        Log::info('get_fiscal_year END');
        return $f_year;
    }


    /**
     * 指定年月の日付と曜日配列を取得する
     */
    function getDayOfWeeks( $year, $month )
    {
        Log::info('getDayOfWeeks START');

        $dayOfWeeks = array();
        $week = array( "日", "月", "火", "水", "木", "金", "土" );

        $sDate = new DateTime();
        $sDate->setDate($year,$month,1);
        $thisDate = clone $sDate;
        while(true) {
            if( $sDate->format('m') != $thisDate->format('m')  ){
                break;
            }

            $dayOfWeek = array(  'day' => $thisDate->format("j")
                               , 'day_of_week' => $week[$thisDate->format("w")] );
            array_push($dayOfWeeks, $dayOfWeek);

            $thisDate = $thisDate->modify('+1 days');
            Log::debug('newDate = ' . $thisDate->format('Y-m-d'));
        }

        foreach($dayOfWeeks as $week){
            Log::debug('retval : day=' . $week['day'] . ', day_of_week=' . $week['day_of_week']);
        }

        Log::info('getDayOfWeeks END');
        return $dayOfWeeks;
    }

    /**
     * 祝日チェック
     */
    function is_holiday($organization_id, $date)
    {
        Log::info('is_holiday START');
        Log::debug('$organization_id=' . $organization_id . ', $date=' . $date);

        // 存在チェック
        $exist = Holiday::whereNull('deleted_at')
                        ->where('organization_id', $organization_id)
                        ->where('date', $date)
                        ->exists();

        Log::debug('ret_val = ' . $exist);
        Log::info('is_holiday END');
        return $exist;
    }

    /**
     * 指定桁数で切り上げ
     */
    function ceil_plus($value, $precision = 1)
    {
        return round($value + 0.5 * pow(0.1, $precision), $precision, PHP_ROUND_HALF_DOWN);
    }

    /**
     * 指定桁数で切り捨て
     */
    function floor_plus($value, $precision = 1)
    {
        return round($value - 0.5 * pow(0.1, $precision), $precision, PHP_ROUND_HALF_UP);
    }

    /**
     * 小数点以下第1位で0.50.5単位で切り捨て
     */
    function floor_half($value)
    {
        Log::info('floor_half START');
        Log::debug('input value = ' . $value);

        $value_disit = 0.0;
        $ret_val = floor($value);
        if( 0.5 <= $value - floor($value) ){
            $ret_val += 0.5;
        }

        Log::debug('output value = ' . $ret_val);
        Log::info('floor_half END');
        return $ret_val;
    }

    //--------------------------------------------------------------------------------------------------
    //-- parameter テーブル関連
    //--------------------------------------------------------------------------------------------------

    /**
     * parameterテーブルからnameをキーにvalueを取得
     */
    public function get_param_value( $organization_id , $param_name )
    {
        Log::info('get_param_value START');

        $value = Parameter::whereNull('deleted_at')
                          ->where('organization_id', $organization_id)
                          ->where('name'           , $param_name)
                          ->value('value');

        Log::debug('organization_id = ' . $organization_id);
        Log::debug('param_name      = ' . $param_name);
        Log::debug('value           = ' . $value);

        Log::info('get_param_value END');
        return $value;
    }


}
