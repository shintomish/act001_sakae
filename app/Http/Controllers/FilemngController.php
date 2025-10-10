<?php

namespace App\Http\Controllers;

use App\Models\UploadUser;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \FilesystemIterator;

class FilemngController extends Controller
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
        Log::info('filemng index START');

        // BookよりGet -> Json 2021/12/23
        // $folderNo = Helper::instance()->getInformation();
        // var_dump($folderNo[0]->name);
        // $u_id = $folderNo[0]->name;

        // ログインユーザーのユーザー情報Userを取得する
        $users = $this->auth_user_info();
        $admin_flg = $users->admin_flg;

        // Jsonより取得
        $jsonfile = storage_path() . "/app/userdata/customer_info_". $users->id. ".json";
        $jsonUrl = $jsonfile; //JSONファイルの場所とファイル名を記述
        $customer_id = 0;
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $obj = json_decode($json, true);
            $obj = $obj["res"]["info"];
            foreach($obj as $key => $val) {
                $customer_id = $val["status"];
            }
            // Log::info('client postUpload  jsonUrl OK');
        } else {
            // echo "データがありません";
            // Log::info('client postUpload  jsonUrl NG');

        }

        Log::info('filemng index $customer_id = ' . print_r($customer_id, true));

        // 選択された顧客IDからCustomer情報(フォルダー名)を取得する
        // $customers  = $this->auth_user_foldername($u_id);
        $customers  = $this->auth_user_foldername($customer_id);

        // 2023/08/18
        $uploadusers = DB::table('uploadusers')
            ->where('customer_id','=',$customer_id)
            ->whereNull('deleted_at')
            ->first();

        // Log::info('filemng index $txt_memo = ' . print_r($txt_memo, true));

        $compacts = compact( 'customers','admin_flg','uploadusers' );
        Log::info('filemng index END');

        return view('filemng.post', $compacts );
    }

    public function post(Request $request)
    {
        Log::info('filemng post START');

        //need to debug query
        // $ret = AppHelper::instance()->startQueryLog();

        //some code that executes queries
        // $ret = AppHelper::instance()->showQueries();

        // Jsonに設定 2021/12/23
        // BookにSet
        // $information = Helper::instance()->setInformation($u_id);

        // Requestより顧客id(customer_id)を取得
        $output = $request->name;
        $customer_id = intval($output);

        // ログインユーザーのユーザー情報Userを取得する
        $users = $this->auth_user_info();
        $admin_flg = $users->admin_flg;

        $jsonfile = storage_path() . "/app/userdata/customer_info_". $users->id. ".json";
        $arr = array(
            "res" => array(
                "info" => array(
                    [
                        "status"     => $customer_id
                    ]
                )
            )
        );
        $arr = json_encode($arr);
        file_put_contents($jsonfile , $arr);

        Log::info('filemng post $customer_id = ' . print_r($customer_id, true));

        // 選択された顧客IDからCustomer情報(フォルダー名)を取得する
        $customers = Customer::where('id',$customer_id)->first();
        
        // 2023/08/18
        $uploadusers = DB::table('uploadusers')
            ->where('customer_id','=',$customer_id)
            ->whereNull('deleted_at')
            ->first();

        // Log::info('filemng post $txt_memo = ' . print_r($txt_memo, true));

        $compacts = compact( 'customers','admin_flg','uploadusers' );
        Log::info('filemng post END');

        return view('filemng.post', $compacts );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UploadUser $uploaduser, Request $request)
    {
        Log::info('filemng show START');

        Log::info('filemng show END');

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store($u_idin)
    public function store(Request $request)
    {
        Log::info('filemng store START');

        // ログインユーザーのユーザー情報Userを取得する
        $users = $this->auth_user_info();
        $admin_flg = $users->admin_flg;

        // Jsonより取得
        $jsonfile = storage_path() . "/app/userdata/customer_info_". $users->id. ".json";
        $jsonUrl = $jsonfile; //JSONファイルの場所とファイル名を記述
        $customer_id = 0;
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $obj = json_decode($json, true);
            $obj = $obj["res"]["info"];
            foreach($obj as $key => $val) {
                $customer_id = $val["status"];
            }
            // Log::info('client postUpload  jsonUrl OK');
        } else {
            // echo "データがありません";
            // Log::info('client postUpload  jsonUrl NG');

        }

        // 選択された顧客IDからCustomer情報(フォルダー名)を取得する
        // $customers  = $this->auth_user_foldername($u_id);
        $customers  = $this->auth_user_foldername($customer_id);
                
        // 2023/08/18
        $uploadusers = DB::table('uploadusers')
            ->where('customer_id','=',$customer_id)
            ->whereNull('deleted_at')
            ->first();

        $compacts = compact( 'customers','admin_flg','uploadusers' );

        Log::info('filemng store END');

        return view('filemng.post', $compacts );

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // 2025/10/25
    // ZIP化ロジックを改良し、
    // ✅ 再帰的に全階層のファイルを取得
    // ✅ 同名ファイルの上書きを防止（相対パス保持）
    // ✅ ファイル名の文字化け対策（CP932変換＋フォールバック）
    // ✅ 追加失敗をログ出力
    // ✅ タイムアウト防止（set_time_limit(0))
    public function alldwonload()
    {
        Log::info('filemng alldwonload START');

        // ログインユーザー情報取得
        $users = $this->auth_user_info();
        $admin_flg = $users->admin_flg;

        // 顧客IDの取得（JSONファイルから）
        $jsonfile = storage_path("app/userdata/customer_info_{$users->id}.json");
        $customer_id = 0;
        if (file_exists($jsonfile)) {
            $json = file_get_contents($jsonfile);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $obj = json_decode($json, true);
            if (!empty($obj["res"]["info"])) {
                foreach ($obj["res"]["info"] as $val) {
                    $customer_id = $val["status"];
                }
            }
        }

        // 顧客フォルダー情報取得
        $customers = $this->auth_user_foldername($customer_id);
        $foldername = $customers->foldername;
        $business_name = $customers->business_name;

        // 実際のファイルパス
        $folderpath = storage_path("app/userdata/{$foldername}");

        // 一時ZIPファイル（英数字のみで一意な名前）
        $tmpZipFile = uniqid('download_') . '.zip';
        $zipFullPath = storage_path("tmp/{$tmpZipFile}");

        // ZipArchiveを初期化
        $zip = new \ZipArchive();
        $result = $zip->open($zipFullPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        if ($result !== true) {
            Log::error("ZIPオープン失敗: result={$result}");
            return false;
        }

        // タイムアウト無効化（大量ファイル対策）
        set_time_limit(0);

        /**
         * 再帰的に全ファイルを取得する関数
         */
        $getAllFiles = function ($dir) use (&$getAllFiles) {
            $files = [];
            foreach (array_diff(scandir($dir), ['.', '..']) as $item) {
                $path = "$dir/$item";
                if (is_dir($path)) {
                    $files = array_merge($files, $getAllFiles($path));
                } else {
                    $files[] = $path;
                }
            }
            return $files;
        };

        // 全ファイル取得（多階層対応）
        $filePaths = $getAllFiles($folderpath);
        Log::info('ZIP対象ファイル数: ' . count($filePaths));

        foreach ($filePaths as $filepath) {
            // ZIP内での相対パスを保持
            $relativePath = str_replace($folderpath . '/', '', $filepath);

            // ファイル名文字コード変換（Windows対応）
            $encodedPath = @mb_convert_encoding($relativePath, 'CP932', 'UTF-8');
            if ($encodedPath === false) {
                Log::warning("CP932変換失敗: {$relativePath}");
                $encodedPath = $relativePath; // フォールバック
            }

            // ZIPへ追加
            if (!$zip->addFile($filepath, $encodedPath)) {
                Log::warning("ZIP追加失敗: {$filepath}");
            }
        }

        $zip->close();
        Log::info("ZIP作成完了: {$zipFullPath}");

        // ZIPファイル存在確認
        if (File::exists($zipFullPath)) {
            $downloadName = $business_name . '_download.zip';
            Log::info('filemng alldwonload 完了・ダウンロード開始');

            // ダウンロード＋送信後削除
            return response()->download($zipFullPath, $downloadName, [])
                            ->deleteFileAfterSend(true);
        } else {
            Log::error('ZIPファイルが存在しません: ' . $zipFullPath);

            // 失敗時のフォールバック表示
            $uploadusers = DB::table('uploadusers')
                ->where('customer_id', '=', $customer_id)
                ->whereNull('deleted_at')
                ->first();

            $compacts = compact('customers', 'admin_flg', 'uploadusers');
            return view('filemng.post', $compacts);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function alldelete()
    {
        Log::info('filemng alldelete START');

        // ログインユーザーのユーザー情報Userを取得する
        $users = $this->auth_user_info();
        $admin_flg = $users->admin_flg;

        // Jsonより取得
        $jsonfile = storage_path() . "/app/userdata/customer_info_". $users->id. ".json";
        $jsonUrl = $jsonfile; //JSONファイルの場所とファイル名を記述
        $customer_id = 0;
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $obj = json_decode($json, true);
            $obj = $obj["res"]["info"];
            foreach($obj as $key => $val) {
                $customer_id = $val["status"];
            }
            // Log::info('client postUpload  jsonUrl OK');
        } else {
            // echo "データがありません";
            // Log::info('client postUpload  jsonUrl NG');
        }

        // 選択された顧客IDからCustomer情報(フォルダー名)を取得する
        // $customers  = $this->auth_user_foldername($u_id);
        $customers  = $this->auth_user_foldername($customer_id);
        $foldername = $customers->foldername;
        $folderpath = 'app/userdata/' . $foldername;

        //処理制限時間を外す
        set_time_limit(0);

        // file delete
        $path   = storage_path($folderpath);
        $this->remove_delete_files($path);

        // directory delete
        // $path   = storage_path($folderpath);
        // $this->remove_delete_directory($path);
                
        // 2023/08/18
        $uploadusers = DB::table('uploadusers')
            ->where('customer_id','=',$customer_id)
            ->whereNull('deleted_at')
            ->first();

        $compacts = compact( 'customers','admin_flg','uploadusers' );

        Log::info('filemng alldelete END');

        return view('filemng.post', $compacts );
    }

    function remove_delete_files($dir){

        Log::info('filemng remove_delete_files START');

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dir,
                 FilesystemIterator::CURRENT_AS_FILEINFO    // 詳細なファイルの情報
                |FilesystemIterator::SKIP_DOTS              // 「.」「..」をスキップ
                |FilesystemIterator::KEY_AS_PATHNAME        // key() としてファイルパス＋ファイル名が得られる
            // ), RecursiveIteratorIterator::LEAVES_ONLY    // 「葉のみ」、つまりファイルのみが取得
            ), RecursiveIteratorIterator::SELF_FIRST      // イテレーションで葉と親を (親から先に) 取り上げます
        );

        foreach($iterator as $pathname => $info){
            $rtn = File::exists($info->getPathname());
            if( $rtn == true ){
                //ファイルの時の処理
                if($info->isFile()){
// Log::debug('filemng remove_delete_files unlink($pathname) = ' . print_r($pathname ,true));
                    unlink($pathname);
                }
            }
        }
        Log::info('filemng remove_delete_files END');
    }

    function remove_delete_directory($dir){

        Log::info('filemng remove_delete_directory START');

        $iterator2 = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dir,
                 FilesystemIterator::CURRENT_AS_FILEINFO    // 詳細なファイルの情報
                |FilesystemIterator::SKIP_DOTS              // 「.」「..」をスキップ
                |FilesystemIterator::KEY_AS_PATHNAME        // key() としてファイルパス＋ファイル名が得られる
            // ), RecursiveIteratorIterator::LEAVES_ONLY    // 「葉のみ」、つまりファイルのみが取得
            ), RecursiveIteratorIterator::SELF_FIRST      // イテレーションで葉と親を (親から先に) 取り上げます
        );
        $iterator2->rewind();
        if( $iterator2->valid() == null ) {
            // return;
        }
// Log::debug('filemng remove_delete_directory $iterator2->valid() = ' . print_r($iterator2->valid() ,true));

        foreach($iterator2 as $pathname => $info){
            $rtn = File::exists($info->getPathname());
            if( $rtn == true ){
                //ディレクトリの時の処理
                if($info->isDir()){
// Log::debug('filemng remove_delete_directory $isDir()       = ' . print_r($info->isDir() ,true));
// Log::debug('filemng remove_delete_directory $getPathname() = ' . print_r($info->getPathname() ,true));
                    // /var/www/html/storage/app/userdata/folder0002/クレジット
                    try {
                        $rtn = File::exists($info->getPathname());
                        if( $rtn == true ){
                            $fname = mb_substr($info->getPathname(),34);    //folder0002/預金
                            // $fname = mb_substr($info->getPathname(),46); //預金
Log::debug('filemng remove_delete_directory $fname         = ' . print_r($fname ,true));
                            // Storage::disk('userdata')->deleteDirectory('folder0002/預金');
                            Storage::disk('userdata')->deleteDirectory($fname);
                            break;
                        }
                    } catch (\RunTimeException $e) {
                        Log::error('deleteDirectory exception : ' . $e->getMessage());
                    }
                }
            }
        }
        Log::info('filemng remove_delete_directory END');
    }

    /** 
     * 2023/08/21
     * Uploaduserテーブルの更新
     */
    public function update_api(Request $request)
    {
        Log::info('update_api Filemng Uploaduser START');

        // Log::debug('update_api request = ' .print_r($request->all(),true));
        $id = $request->input('id');
        $txt_memo  = $request->input('txt_memo');

        $counts = array();
        $update = [];
        if( $request->exists('txt_memo')  ) $update['txt_memo']  = $request->input('txt_memo');

        // $update['updated_at'] = date('Y-m-d H:i:s');
        // Log::debug('update_api update : ' . print_r($update,true));

        $status = array();
        DB::beginTransaction();
        Log::info('update_api Filemng Uploaduser beginTransaction - start');
        try{
            // 更新処理
            Uploaduser::where( 'id', $id )->update($update);

            $status = array( 'error_code' => 0, 'message'  => 'Your data has been changed!' );

            DB::commit();
            Log::info('update_api Filemng Uploaduser beginTransaction - end');
        }
        catch(Exception $e){
            Log::error('update_api Filemng Uploaduser exception : ' . $e->getMessage());
            DB::rollback();
            Log::info('update_api Filemng Uploaduser beginTransaction - end(rollback)');
            echo "エラー：" . $e->getMessage();
            $status = array( 'error_code' => 501, 'message'  => $e->getMessage() );
        }

        // ログインユーザーのユーザー情報Userを取得する
        $users = $this->auth_user_info();
        $admin_flg = $users->admin_flg;

        // Jsonより取得
        $jsonfile = storage_path() . "/app/userdata/customer_info_". $users->id. ".json";
        $jsonUrl = $jsonfile; //JSONファイルの場所とファイル名を記述
        $customer_id = 0;
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $obj = json_decode($json, true);
            $obj = $obj["res"]["info"];
            foreach($obj as $key => $val) {
                $customer_id = $val["status"];
            }
            // Log::info('client postUpload  jsonUrl OK');
        } else {
            // echo "データがありません";
            // Log::info('client postUpload  jsonUrl NG');

        }

        // 選択された顧客IDからCustomer情報(フォルダー名)を取得する
        $customers  = $this->auth_user_foldername($customer_id);

        $uploadusers = DB::table('uploadusers')
            ->where('customer_id','=',$customer_id)
            ->whereNull('deleted_at')
            ->first();

        // Log::info('filemng index $txt_memo = ' . print_r($txt_memo, true));

        $compacts = compact( 'customers','admin_flg','uploadusers' );

        // toastrというキーでメッセージを格納
        // session()->flash('toastr', config('toastr.update'));

        Log::info('update_api Filemng Uploaduser END');

        return view('filemng.post', $compacts );

        // return response()->json([ compact('status','counts') ]);
    }

}