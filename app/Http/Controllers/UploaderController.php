<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\ImageUpload;
use App\Models\UploadUser;

// use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
use Flow\Config as FlowConfig;
use Flow\Request as FlowRequest;


// use Storage;
// use Illuminate\Http\UploadedFile;
// use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
// use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
// use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
// use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
// use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploaderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * postUpload_info uploaded file WEB ROUTE
     * @param Request request
     * @return JsonResponse
     */
    public function postUpload_info($customer_id)
    {
        Log::info('client postUpload_info  START');

        // ログインユーザーのユーザー情報を取得する
        $user = $this->auth_user_info();
        $u_id = $user->id;
        $o_id = $user->organization_id;

        // ログインユーザーのCustomer情報からフォルダー名を取得する
        $uploadusers     = $this->auth_user_foldername($customer_id);
        $foldername      = $uploadusers->foldername;
        $business_name   = $uploadusers->business_name;
        if (isset($uploadusers->check_flg)) {
            $check_flg   = $uploadusers->check_flg; //ファイル無し(1):ファイル有り(2)
        } else {
            $check_flg   = 1;
        }
        $folderpath      = 'app'. '/' . 'userdata'. '/' . $foldername;

        // 年月取得
        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        $dateNew = ($now->format('Y/m'));

        $compacts = compact( 'u_id','o_id', 'customer_id', 'foldername','business_name','folderpath','check_flg','dateNew' );

        Log::info('client postUpload_info $compacts[customer_id]  = ' . print_r($compacts['customer_id'] ,true));

        // * ログインユーザーのCustomerオブジェクトをjsonにSetする
        $this->json_put_info_set($u_id, $o_id,$customer_id, $foldername, $business_name,$folderpath,$check_flg,$dateNew);

        Log::info('client postUpload_info  END');

        return  $compacts;

    }

    /**
     * postUpload uploaded file WEB ROUTE
     * @param Request request
     * @return JsonResponse
     */
    public function postUpload($customer_id, Request $request)
    {
        Log::info('client postUpload  START');

        $jsonfile = storage_path() . "/tmp/customer_info_status_". $customer_id. ".json";
        $jsonUrl = $jsonfile; //JSONファイルの場所とファイル名を記述
        $status = true;
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            
            // 2023/09/20
            $obj = [];

            $obj = json_decode($json, true);

            // 2023/09/20
            if(empty($obj)){
                $obj[0] = $this->postUpload_info($customer_id);
                Log::info('client postUpload empty');
            } else {
                // $obj = $obj["res"]["info"];
                $obj[0] = $this->postUpload_info($customer_id);
                Log::info('client postUpload not empty');
            }

            // $obj = json_decode($json, true);
            // $obj = $obj["res"]["info"];
            // foreach($obj as $key => $val) {
            //     $status = false;
            //     $status = $val["status"];
            // }
            // Log::info('client postUpload  jsonUrl OK');
        } else {
            // echo "データがありません";
            // Log::info('client postUpload  jsonUrl NG');

        }

        // ログインユーザーのユーザー情報を取得する
        if($status == false) {
            $ret  = $this->postUpload_info($customer_id);

            // Statusを変える
            $status = 99;
            $this->json_put_status($status,$customer_id);
        }

        // * ログインユーザーのCustomerオブジェクトをjsonから取得する
        $compacts = $this->json_get_info($customer_id);

        $config = new FlowConfig();

        // tmpフォルダをユーザーごとに変更
        // $tmp = '/tmp'. '/' . $compacts['u_id'];
        // tmpフォルダをCustomeridごとに変更
        $tmp = '/tmp'. '/' . $customer_id;

        if(!file_exists( storage_path() . $tmp)){
            mkdir( storage_path() . $tmp, $mode = 0777, true);
        }
        $config->setTempDir(storage_path() . $tmp);
        $config->setDeleteChunksOnSave(false);
        $file = new \Flow\File($config);

        $request = new FlowRequest();

        $totalSize = $request->getTotalSize();

        // アップロード可能なサイズは 30MB
        $maxtataldisp = 30;
        $maxtatalsize = (1024 * 1024 * $maxtataldisp);
        if ($totalSize && $totalSize > $maxtatalsize)
        {
            $errormsg = 'ファイルサイズが大きすぎます。アップロード可能なサイズは '. $maxtataldisp. ' MBまでです。';
            Log::info('client postUpload  failesize to big ');
            // Statusを変える
            $status = false;
            $this->json_put_status($status,$customer_id);
            //400 Bad Request	一般的なクライアントエラー
            return \Response::json(['error'=>$errormsg,'status'=>'BG'],400);

        }

        $uploadFile = $request->getFile();
     
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($file->checkChunk()) {
                header("HTTP/1.1 200 Ok");
                Log::info('client postUpload HTTP/1.1 200 Ok ');
            } else {
                //HTTP のレスポンスコード 204 No Content は、リクエストが成功した事を示しますが、
                //クライアントは現在のページから遷移する必要はありません。
                //レスポンスコード 204 が返された場合は、デフォルトでキャッシュ可能になっています。
                //そのようなレスポンスには、 ETag ヘッダーが含まれています。
                header("HTTP/1.1 204 No Content");
                Log::info('client postUpload HTTP/1.1 204 No Content ');
                return ;
            }
        } else {
            if ($file->validateChunk()) {
                // Log::info('client postUpload validateChunk ok ');
                $file->saveChunk();
            } else {

                // // Statusを変える
                // $status = false;
                // $this->json_put_status($status,$customer_id);

                //「400 Bad Request」は、不正な構文、無効なリクエストメッセージフレーミング、
                //または不正なリクエストルーティングのために、サーバーがクライアントによって
                //送信されたリクエストを処理できなかったことを示すHTTPステータスコードです。
                // error, invalid chunk upload request, retry
                header("HTTP/1.1 400 Bad Request");
                Log::debug('client postUpload HTTP/1.1 400 Bad Request ');
                return ;

                // // strage/tmp
                // $file->deleteChunks();
                // Log::debug('client postUpload HTTP/1.1 400 Bad Request ');
                // Log::debug('client postUpload_info  validateChunk not   $uploadFile[name]  = ' . print_r($uploadFile['name'] ,true));

                $errormsg = 'アップロード出来ませんでした。';
                // Indicate that we are not done with all the chunks.
                return \Response::json(['error'=>$errormsg,'status'=>'NG'], 400);
                // return redirect('topclient/index')->with('message', '送信処理出来ませんでした。');
            }
        }

        $fileName = $uploadFile['name'];         // FileName
        $fileSize = $request->getTotalSize();    // FileSize
        $filedir = '/app/userdata/' . $compacts['foldername'] . '/';

        if(!file_exists( storage_path() . $filedir)){
            mkdir( storage_path() . $filedir, $mode = 0777, true);
        }

        //2023/09/14 Middleware\ActlogMiddleware::classをコメント
        // $tmp_name = $uploadFile['tmp_name'];     // tmp_name
        // if(!file_exists( $tmp_name)){
        //     mkdir( $tmp_name, $mode = 0777, true);
        // }
        // Log::debug('client postUpload  $tmp_name = ' . print_r($tmp_name ,true));

        // 2023/02/13 ERROR: Undefined array key "extension"
        // $identifier = md5($uploadFile['name']).'-' . time() ;
        // $p = pathinfo($uploadFile['name']);
        // /* hashファイル名と拡張子を結合 */
        // $identifier .= "." . $p['extension'];

        /* アップロードパス */
        // $path =  $filedir . $identifier;
        $path =  $filedir . $fileName;
        $storage_path = storage_path() . $path;

        Log::info('client postUpload  $fileName = ' . print_r($fileName,true));
        if ($file->validateFile() && $file->save($storage_path))
        {
            // strage/tmp
            $file->deleteChunks();

            try {
                DB::beginTransaction();
                Log::info('beginTransaction - client postUpload saveFile start');

                $imageUpload = new ImageUpload();
                $imageUpload->filename        = $fileName;
                $imageUpload->organization_id = $compacts['o_id'];
                $imageUpload->user_id         = $compacts['u_id'];
                $imageUpload->customer_id     = $compacts['customer_id'];
                $imageUpload->filesize        = $fileSize;
                $imageUpload->save();               //  Inserts

                $data['count'] = UploadUser::where('customer_id',$compacts['customer_id'])->count();

                //更新
                if( $data['count'] > 0 ) {
                    $uploadusers = DB::table('uploadusers')
                    // ユーザーの絞り込み
                    ->where('customer_id',$compacts['customer_id'])
                    // 削除されていない
                    ->whereNull('deleted_at')
                    ->update([
                        'yearmonth'  =>  $compacts['dateNew'],
                        'check_flg'  =>  2,                     // ファイル無し(1):ファイル有り(2)
                        'prime_flg'  =>  3,                     // 優先順位 -(1): 低(2): 中(3): 高(4) 2022/11/04
                        // 'created_at' =>  now()  2022/12/16
                        'updated_at' =>  now()
                    ]);
                //追加
                } else {
                    $uploaduser = new UploadUser();
                    $uploaduser->foldername      = $compacts['foldername'];     // フォルダー000x
                    $uploaduser->business_name   = $compacts['business_name'];  // 顧客名
                    $uploaduser->organization_id = $compacts['o_id'];
                    $uploaduser->customer_id     = $compacts['customer_id'];
                    $uploaduser->yearmonth       = $compacts['dateNew'];        // 年月 2021/08
                    $uploaduser->check_flg       = 2;                           // ファイル無し(1):ファイル有り(2)
                    $uploaduser->prime_flg       = 3;                           // 優先順位 -(1): 低(2): 中(3): 高(4) 2022/11/04
                    $uploaduser->save();                                        // Inserts
                }

                DB::commit();
                Log::info('beginTransaction - client postUpload saveFile end(commit)');
            }
            catch(\QueryException $e) {
                Log::error('exception : ' . $e->getMessage());
                DB::rollback();
                Log::info('beginTransaction - client postUpload saveFile end(rollback)');
                // Statusを変える
                $status = false;
                $this->json_put_status($status,$customer_id);
                $errormsg = 'アップロード出来ませんでした。';
                return \Response::json(['error'=>$errormsg,'status'=>'NG'], 400);
            }

            // Statusを変える
            $status = false;
            $this->json_put_status($status,$customer_id);

            Log::info('client postUpload  END');

            // $data = 'ok';
            // return \Response::json($data, 200);
            return \Response::json(['error'=>'アップロードが正常に終了しました。','status'=>'OK'], 200);
        } else {
            // This is not a final chunk, continue to upload
            Log::info('client postUpload  This is not a final chunk, continue to upload ');
        }

    }
    /**
     * ログインユーザーのCustomerオブジェクトをSetする
     */
    public function json_put_status($status,$customer_id)
    {
        Log::info('client json_put_status  START');

        $jsonfile = "";
        $arr = array(
            "res" => array(
                "info" => array(
                    [
                        "status"     => $status
                    ]
                )
            )
        );

        $arr_status = json_encode($arr);
        $jsonfile = storage_path() . "/tmp/customer_info_status_". $customer_id. ".json";

        file_put_contents($jsonfile , $arr_status);
        Log::info('client json_put_status  END');
    }

    /**
     * ログインユーザーのCustomerオブジェクトをSetする
     */
    public function json_put_info_set($u_id, $o_id,$customer_id, $foldername, $business_name,$folderpath,$check_flg,$dateNew)
    {
        Log::info('client json_put_info_set  START');

        $arr = array(
            "res" => array(
                "info" => array(
                    [
                        "u_id"           => $u_id,
                        "o_id"           => $o_id,
                        "customer_id"    => $customer_id,
                        "foldername"     => $foldername,
                        "business_name"  => $business_name,
                        "folderpath"     => $folderpath,
                        "check_flg"      => $check_flg,
                        "dateNew"        => $dateNew
                    ]
                )
            )
        );

        $arr = json_encode($arr);
        $jsonfile = storage_path() . "/tmp/customer_info_". $customer_id. ".json";

        file_put_contents($jsonfile , $arr);
        Log::info('client json_put_info_set  END');
    }

    /**
     * ログインユーザーのCustomerオブジェクトを取得する
     */
    public function json_get_info($customer_id)
    {
        Log::info('client json_get_info  START');

        $jsonfile = storage_path() . "/tmp/customer_info_". $customer_id. ".json";

        // Log::debug('client json_get_info  jsonfile = ' . print_r($jsonfile,true));

        // $jsonUrl = "customer_info.json"; //JSONファイルの場所とファイル名を記述
        $jsonUrl = $jsonfile; //JSONファイルの場所とファイル名を記述
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            
            // 2023/09/20
            $obj = [];

            $obj = json_decode($json, true);

            // 2023/09/20
            if(empty($obj)){
                $obj[0] = $this->postUpload_info($customer_id);
                Log::info('client json_get_info empty');
            } else {
                $obj = $obj["res"]["info"];
                Log::info('client json_get_info not empty');
            }

            foreach($obj as $key => $val) {
                $u_id          = $val["u_id"];
                $o_id          = $val["o_id"];
                $customer_id   = $val["customer_id"];
                $foldername    = $val["foldername"];
                $business_name = $val["business_name"];
                $folderpath    = $val["folderpath"];
                $check_flg     = $val["check_flg"];
                $dateNew       = $val["dateNew"];
            }
            // Log::info('client json_get_info  OK');
        } else {
            echo "データがありません";
            Log::info('client json_get_info  NG');
        }
        $compacts = compact( 'u_id','o_id', 'customer_id', 'foldername','business_name','folderpath','check_flg','dateNew' );

        Log::info('client json_get_info  END');
        return  $compacts;
    }

    /**
     * Delete uploaded file WEB ROUTE
     * @param Request request
     * @return JsonResponse
     */
    public function delete(Request $request){

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $customer_id = $request->Input('customer_id');

        // ログインユーザーのユーザー情報を取得する
        $user = $this->auth_user_info();
        $u_id = $user->id;

        // ログインユーザーのCustomer情報からフォルダー名を取得する
        $uploadusers     = $this->auth_user_foldername($customer_id);
        $foldername      = $uploadusers->foldername;
        $business_name   = $uploadusers->business_name;
        $filePath        = 'app'. '/' . 'userdata'. '/' . $foldername;

        $file = $request->filename;

        //delete timestamp from filename
        $temp_arr = explode('_', $file);
        if ( isset($temp_arr[0]) ) unset($temp_arr[0]);
        $file = implode('_', $temp_arr);

        $finalPath = storage_path("app/".$filePath);

        if ( unlink($finalPath.$file) ){
        return response()->json([
            'status' => 'ok'
            ], 200);
        }
        else{
        return response()->json([
            'status' => 'error'
            ], 403);
        }
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
            // maxはキロバイト指定になるので、max:1024と指定すると、
            // 1メガバイト以上だとエラーが出る OUTLOOKは20M
            // 300 MB  307200 KB
            // 500 MB  512000 KB
            // 'file'     => 'required|file',
            'file'     => 'required|file|max:512000',
        ];

        $messages = [
            'file.required'  => 'ファイルを選択してください。',
            'file.file'      => 'ファイルアップロード出来ませんでした。',
            'file.max'       => 'ファイルサイズが大きすぎます。'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }

}
