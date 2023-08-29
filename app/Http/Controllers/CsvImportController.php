<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\ControlUser;
use App\Models\Spedelidate;
use App\Models\Yrendadjust;
use App\Models\Wokprocbook;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Session\Middleware\StartSession;
// use Illuminate\View\Middleware\ShareErrorsFromSession;

use Illuminate\Routing\Controller as BaseController;

class CsvImportController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('csvimport store START');

        // アップロードファイルに対してのバリデート
        $validator = $this->validateUploadFile($request);

        if ($validator->fails() === true){
            return redirect('customer')->with('message', $validator->errors()->first('csv_file'));
        }
        Log::info('csvimport store START2');
        // if ($validator->fails()) {
        //     Log::info('csvimport validator error');
        //     return redirect('customer')->withErrors($validator)->withInput();
        //     // return redirect('customer')->with('message', $validator->errors()->first('csv_file'));
        // }

        // CSVファイルをサーバーに保存
        $temporary_csv_file = $request->file('csv_file')->store('csv');

        $fp = fopen(storage_path('app/') . $temporary_csv_file, 'r');

        // 一行目（ヘッダ）読み込み
        $headers = fgetcsv($fp);
        $column_names = [];

        $column_ok = [];

        // 2022/12/27
        $encoding    = mb_convert_encoding($headers, "utf-8", "sjis");
        // Log::debug('csvimport CSVヘッダ $headers : ' . print_r($encoding,true));

        // CSVヘッダ確認
        foreach ($encoding as $header) {
            // Log::debug('csvimport CSVヘッダ $header   : ' . $header);
            // $result = Customer::retrieveCustomerColumnsByValue($header, "utf-8", "sjis");
            $result = Customer::retrieveCustomerColumnsByValue($header);
            // Log::debug('csvimport CSVヘッダ $result   : ' . $result);
                switch($result) {
                    case ('');
                        $column_ok[] = '1';
                        break;
                    default:
                        break;
                }

            if ($result === null) {
                fclose($fp);
                Storage::delete($temporary_csv_file);
                Log::info('csvimport CSVヘッダ error');

                // session()->flash('toastr', config('toastr.upload_error1'));
                // return redirect()->route('customer');
                return redirect('customer')
                    ->with('message', '登録に失敗しました。CSVファイル(SJIS)のフォーマットが正しいことを確認してださい。');
            }
            $column_names[] = $result;
        }
        Log::info('csvimport CSVヘッダ確認 end');
        $registration_errors_list = [];
        $update_errors_list       = [];
        $detail                   = [];
        $i = 0;
        $detail_column_no = 0;

        // Log::debug('csvimport CSVヘッダ $row1 : ' . print_r(fgetcsv($fp),true));
        // Log::debug('csvimport CSVヘッダ $row2 : ' . print_r(fgetcsv($fp),true));
        // TODO:サイズが大きいCSVファイルを読み込む場合、この処理ではメモリ不足になる可能性がある為改修が必要になる
        while ($row = fgetcsv($fp)) {

            // Excelで編集されるのが多いと思うのでSJIS-win→UTF-8へエンコード
            mb_convert_variables('UTF-8', 'SJIS-win', $row);

            $is_registration_row = false;

            foreach ($column_names as $column_no => $column_name) {

                // Log::debug('csvimport CSV内容     [$column_no]   : ' . $column_no);
                // Log::debug('csvimport CSV内容 $row[$column_no]   : ' . $row[$column_no]);
                // idがなければ登録、あれば更新と判断
                if ($column_name === 'id' && $row[$column_no] === '') {
                    $is_registration_row = true;
                }

                // Table挿入処理のため$detail[$column_no]に値を代入
                $detail[$column_no] = $row[$column_no];

                // ErroCheck
                $detail_column_no   = $column_no;

                // 新規登録か更新かのチェック
                if($is_registration_row === true){
                    if ($column_name !== 'id') {
                        $registration_csv_list[$i][$column_name] = $row[$column_no] === '' ? null : $row[$column_no];
                    }
                } else {
                    $update_csv_list[$i][$column_name] = $row[$column_no] === '' ? null : $row[$column_no];
                }
            }

            // バリデーションチェック
            $validator = \Validator::make(
                $is_registration_row === true ? $registration_csv_list[$i] : $update_csv_list[$i],
                $this->defineValidationRules(),
                $this->defineValidationMessages()
            );

            if ($validator->fails() === true) {
                if ($is_registration_row === true) {
                    $registration_errors_list[$i + 2] = $validator->errors()->all();
                } else {
                    $update_errors_list[$i + 2] = $validator->errors()->all();
                }
            }

            // if($detail_column_no == 88 ) {
            if($detail_column_no == 90 ) {
                // ～CustomerTable 挿入処理～
                if ($this->detailUploadFile($detail) === false) {
                    // session()->flash('toastr', config('toastr.upload_error3'));
                    // return redirect()->route('customer');
                    return redirect('customer')->with('message', '新規登録処理に失敗しました。');
                }
            } else {
                return redirect('customer')->with('message', 'CSVファイルが違います。');
            }
            $i++;
        }

        // バリデーションエラーチェック
        if (count($registration_errors_list) > 0 || count($update_errors_list) > 0) {
            return redirect('customer')
                ->with('errors', ['registration_errors' => $registration_errors_list, 'update_errors' => $update_errors_list]);
        }

        // 既存更新処理
        // if (isset($update_csv_list) === true) {
        //     foreach ($update_csv_list as $update_csv) {
        //         // ～更新用の処理～
        //         if ($this->fill($update_csv)->save() === false) {

        //             // session()->flash('toastr', config('toastr.upload_error2'));
        //             // return redirect()->route('customer');
        //             return redirect('customer')
        //                 ->with('message', '既存データの更新に失敗しました。（新規登録処理は行われずに終了しました）');
        //         }
        //     }
        // }

        // 新規登録処理
        if (isset($registration_csv_list) === true) {
            foreach ($registration_csv_list as $registration_csv) {
                // ～登録用の処理～
                if ($this->fill($registration_csv)->save() === false) {
                    // session()->flash('toastr', config('toastr.upload_error3'));
                    // return redirect()->route('customer');
                    return redirect('customer')->with('message', '新規登録処理に失敗しました。');
                }
            }
        }
        // ErroCheck
        // if($detail_column_no == 88 ) {
        //     // ～CustomerTable 挿入処理～
        //     if ($this->detailUploadFile($detail) === false) {
        //         // session()->flash('toastr', config('toastr.upload_error3'));
        //         // return redirect()->route('customer');
        //         return redirect('customer')->with('message', '新規登録処理に失敗しました。');
        //     }
        // } else {
        //     return redirect('customer')->with('message', 'CSVファイルが違います。');
        // }

        Log::info('csvimport store END');

        // session()->flash('toastr', config('toastr.upload_ok'));
        // return redirect()->route('customer');
        return redirect('customer')->with('message', 'CSV登録が完了しました。' );
    }
    /**
     *
     * CustomerTable 挿入処理
     *
     */
    private function detailUploadFile($detail)
    {
        Log::info('customer detailUploadFile START');

        DB::beginTransaction();
        Log::info('beginTransaction - detailUploadFile start');

        $organization  = $this->auth_user_organization();

        // * 今年の年を取得2 Book
        $nowyear   = intval($this->get_now_year2());

        // Table挿入処理
        try {
            $customer = new Customer();
            $customer->organization_id     = $organization->id;
            $customer->year                = $nowyear;

            if (isset($detail[0]) === true) {
                $customer->business_code   = $detail[0]; //事業者コード sprintf($format, $detail[0])
            } else {
                $customer->business_code       = "0000000000";
            }

            $customer->business_kana       = $detail[1];
            $customer->business_name       = $detail[2];

            //法人(1):個人事業主(2)
            if (isset($detail[3+2]) === true) {
                if($detail[3+2] === "個人") {
                    $customer->individual_class    = 2;	    //法人(1):個人事業主(2)
                    $customer->closing_month       = 13;	//法人(1-12)[1月～12月]:個人:確定申告(13)
                } else {
                    $customer->individual_class    = 1;	    //法人(1):個人事業主(2)
                    $customer->closing_month       = 1;	    //法人(1-12)[1月～12月]:個人:確定申告(13)

                    //決算月 法人(1-12)[1月～12月]:個人:確定申告(13)
                    // if(isset($detail[36+2]) === true && intval($detail[36+2]) != 0 ){
                    if(isset($detail[36+2]) === true ){
                        $customer->closing_month   = intval($detail[36+2]);	//法人(1-12)[1月～12月]:個人:確定申告(13)
                        // $customer->closing_month       = 13;	//法人(1-12)[1月～12月]:個人:確定申告(13)
                    }
                }
            } else {
                $customer->individual_class    = 1;	//法人(1):個人事業主(2)
            }

            //青色申告 (1):青色 (2):白色'
            if(isset($detail[4+2]) === true) {
                if($detail[4+2] === "青色") {
                    $customer->blue_declaration    = 1;	//青色申告 (1):青色 (2):白色',
                } else {
                    $customer->blue_declaration    = 2;	//青色申告 (1):青色 (2):白色',
                }
            } else {
                if($customer->individual_class === 1){
                    $customer->blue_declaration    = 1;	//青色申告 (1):青色 (2):白色',
                } else {
                    $customer->blue_declaration    = 2;	//青色申告 (1):青色 (2):白色',
                }
            }
            $customer->email               = $detail[3];    //2022/12/27
            $customer->business_zipcode    = $detail[5+2].'-'.$detail[6+2];
            $customer->business_address    = $detail[8+2];
            $customer->business_tell       = $detail[9+2].'-'.$detail[10+2].'-'.$detail[11+2];
            $customer->memo_1  		       = $detail[26+2];
            $customer->industry  	       = $detail[31+2];   //2022/05/22
            $customer->tax_office  	       = $detail[35+2];
            $customer->represent_name      = $detail[40+2];
            $customer->represent_kana      = $detail[41+2];
            $customer->represent_zipcode   = $detail[42+2].'-'.$detail[43+2];
            $customer->represent_address   = $detail[45+2];
            $customer->represent_tell      = $detail[46+2].'-'.$detail[47+2].'-'.$detail[48+2];

            $customer->active_cancel       = 1;
            $customer->notificationl_flg   = 2;
            $customer->consumption_tax     = 1;
            $customer->save();                  //  Inserts

            $customer = Customer::orderBy('id', 'desc')->first();
            $str                        = sprintf("%04d", $customer->id);
            $foldername                 = 'folder'. $str;
            $customer->foldername       = $foldername;
            $customer->save();         //  Inserts

            //active_cancel アクティブ/解約 1:契約 2:SPOT 3:解約
            if($customer->active_cancel === 1) {
                // $advisorsfee = new Advisorsfee();       // 顧問料金
                // $advisorsfee->organization_id = $organization->id;
                // $advisorsfee->custm_id        = $customer->id;
                // $advisorsfee->year            = $nowyear;
                // $advisorsfee->advisor_fee     = 0; // 顧問料
                // $advisorsfee->save();                   //  Inserts
                // Log::info('beginTransaction - detailUploadFile advisorsfee(commit)');

                //individual_class 法人(1):個人事業主(2)
                if($customer->individual_class === 1) {
                    $spedelidate = new Spedelidate();       // 納期の特例
                    $spedelidate->organization_id  = $organization->id;
                    $spedelidate->custm_id         = $customer->id;
                    $spedelidate->year             = $nowyear;
                    $spedelidate->officecompe      = 0;       //'役員報酬
                    $spedelidate->employee         = 0;       //'従業員
                    $spedelidate->adept_flg        = 1;       //'達人フラグ
                    $spedelidate->payslip_flg      = 1;       //'納付書作成
                    $spedelidate->declaration_flg  = 1;       //'0円納付申告
                    $spedelidate->save();                     //  Inserts
                    Log::info('beginTransaction - detailUploadFile spedelidate(commit)');

                    $yrendadjust = new Yrendadjust();       // 年末調整
                    $yrendadjust->organization_id  = $organization->id;
                    $yrendadjust->custm_id         = $customer->id;
                    $yrendadjust->year             = $nowyear;
                    $yrendadjust->absence_flg      = 1;   //'年調の有無 1:無 2:有
                    $yrendadjust->communica_flg    = 1;   //'伝達手段
                    $yrendadjust->salary_flg       = 1;   //'給与情報 1:未 2:済
                    $yrendadjust->refund_flg       = 1;   //'申請すれば還付あり 1:× 2:○
                    $yrendadjust->declaration_flg  = 1;   //'/0円納付申告 1:× 2:○
                    $yrendadjust->annual_flg       = 1;   //'年調申告 1:× 2:○
                    $yrendadjust->withhold_flg     = 1;   //'源泉徴収票 1:× 2:○
                    $yrendadjust->claim_flg        = 1;   //'請求フラグ 1:× 2:○
                    $yrendadjust->payment_flg      = 1;   //'入金確認フラグ 1:× 2:○
                    $yrendadjust->save();
                    Log::info('beginTransaction - detailUploadFile yrendadjust(commit)');

                    // wokprocbooktsチェック 税理士業務処理簿 2022/05/22
                    // 2022/09/20 整理番号の初期設定
                    $wokprocbooks = DB::table('wokprocbooks')->get();
                    $count  = $wokprocbooks->count();
                    $number = $nowyear . sprintf("%06d", ($count+1));

                    $new_data = new Wokprocbook();
                    $new_data->organization_id  = $organization->id;
                    $new_data->custm_id         = $customer->id;
                    $new_data->year             = $nowyear;
                    // $str                        = $nowyear . sprintf("%06d", $customer->id);
                    $new_data->refnumber        = $number;
                    // $new_data->staff_no         = 7;
                    $new_data->staff_no         = auth::user()->id;
                    $new_data->save();           //  Inserts
                    Log::info('beginTransaction - detailUploadFile Wokprocbook(commit)');
                }
                //E-Mailが入っていたら
                if(isset($detail[3]) === true) {
                    //User
                    $user = new User();
                    $user->organization_id  = $organization->id;
                    $user->user_id          = $customer->id;
                    $user->name             = $customer->represent_name;
                    $user->email            = $customer->email;
                    $user->login_flg        = 1;
                    $user->admin_flg        = 1;
                    $user->password         = Hash::make("user1234");
                    $user->save();           //  Inserts

                    //ControlUser
                    $users = DB::table('users')->get();
                    $count = $users->count();

                    $controluser = new ControlUser();
                    $controluser->organization_id  = $organization->id;
                    $controluser->user_id          = $count;
                    $controluser->customer_id      = $customer->id;
                    $controluser->save();           //  Inserts
                }

            }

            DB::commit();
            Log::info('beginTransaction - detailUploadFile end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('beginTransaction - detailUploadFile end(rollback)');
            return false;
        }

        Log::info('customer detailUploadFile END');

        return true;
    }
    /**
     * アップロードファイルのバリデート
     * （※本来はFormRequestClassで行うべき）
     *
     * @param Request $request
     * @return Illuminate\Validation\Validator
     */
    private function validateUploadFile(Request $request)
    {
        $rules   = [
            // 'csv_file'     => 'required|file|mimetypes:text/plain|mimes:csv,txt',
            'csv_file' => [
                'required',
                'file',
                'mimes:csv,txt',
                'mimetypes:text/plain',
            ],
        ];

        $messages = [
            'csv_file.required'  => 'ファイルを選択してください。',
            'csv_file.file'      => 'ファイルアップロードに失敗しました。',
            'csv_file.mimetypes' => 'ファイル形式が不正です。',
            'csv_file.mimes'     => 'ファイル拡張子が異なります。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }

    /**
     * バリデーションの定義
     *
     * @return array
     */
    private function defineValidationRules()
    {
        return [
            // CSVデータ用バリデーションルール
            'business_code' => 'required',
        ];
    }

    /**
     * バリデーションメッセージの定義
     *
     * @return array
     */
    private function defineValidationMessages()
    {
        return [
            // CSVデータ用バリデーションエラーメッセージ
            'business_code.required' => '事業者コードを入力してください。',
        ];
    }
}
