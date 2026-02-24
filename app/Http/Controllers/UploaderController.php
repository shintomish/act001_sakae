<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\ImageUpload;
use App\Models\UploadUser;

// 2025/03/18 add
use Illuminate\Http\Request;
// use Illuminate\Http\Request;

// 2024/09/30
// use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// use Symfony\Component\HttpFoundation\Request;    // 2025/03/18 commennt

$request = Request::createFromGlobals();
use Flow\Config as FlowConfig;
use Flow\Request as FlowRequest;
// use League\CommonMark\Extension\CommonMark\Renderer\Block\ThematicBreakRenderer;

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
     // 変数名を変更
    public function postUpload($customer_id, Request $laravelRequest)
    {
        Log::info('client postUpload  START');

        $jsonfile = storage_path() . "/tmp/customer_info_status_". $customer_id. ".json";
        $jsonUrl = $jsonfile;
        $status = true;
        if (file_exists($jsonUrl)) {
            $json = file_get_contents($jsonUrl);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

            $obj = [];
            $obj = json_decode($json, true);

            if(empty($obj)){
                $obj[0] = $this->postUpload_info($customer_id);
                Log::info('client postUpload empty');
            } else {
                $obj[0] = $this->postUpload_info($customer_id);
                Log::info('client postUpload not empty');
            }
        }

        if($status == false) {
            $ret  = $this->postUpload_info($customer_id);
            $status = 99;
            $this->json_put_status($status,$customer_id);
        }

        $compacts = $this->json_get_info($customer_id);

        $config = new FlowConfig();

        $tmp = '/tmp'. '/' . $customer_id;
        $path = storage_path() . $tmp;
        if (!is_dir($path)) {
            try {
                mkdir($path, 0777, true);
            } catch (\Exception $e) {
                if (!is_dir($path)) {
                    throw $e;
                }
            }
        }

        $config->setTempDir(storage_path() . $tmp);
        $config->setDeleteChunksOnSave(false);

        // ✅ 修正1: FlowRequest を別変数で扱い、$laravelRequest を上書きしない
        $flowRequest = new FlowRequest();
        $file = new \Flow\File($config);

        // ✅ 修正2: ファイルサイズチェックは FlowRequest から取得（ファイルオブジェクト不要）
        $totalSize = $flowRequest->getTotalSize();

        $maxtataldisp = 30;
        $maxtatalsize = (1024 * 1024 * $maxtataldisp);
        if ($totalSize && $totalSize > $maxtatalsize)
        {
            $errormsg = 'ファイルサイズが大きすぎます。アップロード可能なサイズは '. $maxtataldisp. ' MBまでです。';
            Log::debug('client postUpload ★filesize to big customer_id = ' . print_r($customer_id ,true));
            $status = false;
            $this->json_put_status($status,$customer_id);
            return \Response::json(['error'=>$errormsg,'status'=>'BG'],400);
        }

        $uploadFile = $flowRequest->getFile();

        // ✅ 修正3: ファイル名長チェック
        $length_strlen = strlen($uploadFile['name']);
        $maxtatallength = 255;
        if ($length_strlen > $maxtatallength)
        {
            $errormsg = 'ファイル名が長過ぎます。アップロード可能なファイル名長は '. $maxtatallength. ' 文字までです。';
            Log::info('client postUpload  filename too long ');
            Log::debug('client postUpload $length_strlen error = ' . print_r($length_strlen ,true));
            Log::debug('client postUpload $uploadFile[name] = ' . print_r($uploadFile['name'] ,true));
            $status = false;
            $this->json_put_status($status,$customer_id);
            return \Response::json(['error'=>$errormsg,'status'=>'BGstrlen'],400);
        }
        Log::debug('client postUpload $length_strlen = ' . print_r($length_strlen ,true));

        // ✅ 修正4: GETリクエストとPOSTリクエストを先に分岐し、
        //           ファイル名処理はPOST（実アップロード）時のみ実行
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($file->checkChunk()) {
                header("HTTP/1.1 200 Ok");
                Log::info('client postUpload HTTP/1.1 200 Ok ');
            } else {
                header("HTTP/1.1 204 No Content");
                Log::info('client postUpload HTTP/1.1 204 No Content ');
                return;
            }
            return; // ✅ GETはここで終了（以降の処理は不要）
        }

        // --- 以下はPOSTリクエストのみ ---

        // ✅ 修正5: $laravelRequest からファイルを取得（nullチェック付き）
        $laravelFile = $laravelRequest->file('file');

        if ($laravelFile === null) {
            // チャンクの途中など、最終チャンク以外はnullの場合がある
            // validateChunk/saveChunk を先に実行
            if ($file->validateChunk()) {
                $file->saveChunk();
                Log::info('client postUpload chunk saved (no laravel file yet)');
                return \Response::json(['status'=>'CHUNK_SAVED'], 200);
            } else {
                header("HTTP/1.1 400 Bad Request");
                Log::debug('client postUpload HTTP/1.1 400 Bad Request (validateChunk failed)');
                return;
            }
        }

        // ✅ 修正6: ファイル名クリーン処理（nullチェック後に実行）
        $originalName = $laravelFile->getClientOriginalName();
        $cleanName = trim($originalName);
        $cleanName = preg_replace('/\s+/', ' ', $cleanName);
        $cleanName = preg_replace('/　+/', '　', $cleanName);
        $cleanName = preg_replace('/[\\\\\\/\\:\\*\\?\\"<>\\|]/', '_', $cleanName);

        $filename00 = pathinfo($cleanName, PATHINFO_FILENAME);
        $extension  = pathinfo($cleanName, PATHINFO_EXTENSION);

        if ($file->validateChunk()) {
            $file->saveChunk();
        } else {
            header("HTTP/1.1 400 Bad Request");
            Log::debug('client postUpload HTTP/1.1 400 Bad Request ');
            return;
        }

        $fileSize = $flowRequest->getTotalSize();
        $filedir  = '/app/userdata/' . $compacts['foldername'] . '/';

        // 重複がないファイル名を探す
        $counter = 1;
        $uniqueFilename = $filename00 . '.' . $extension;
        while (file_exists(storage_path() . $filedir . $uniqueFilename)) {
            $uniqueFilename = $filename00 . '_' . $counter . '.' . $extension;
            Log::debug('client postUpload file_exists $uniqueFilename = ' . print_r($uniqueFilename ,true));
            $counter++;
        }
        $fileName = $uniqueFilename;

        $path = storage_path() . $filedir;
        if (!is_dir($path)) {
            try {
                mkdir($path, 0777, true);
            } catch (\Exception $e) {
                if (!is_dir($path)) {
                    throw $e;
                }
            }
        }

        $savePath      = $filedir . $fileName;
        $storage_path  = storage_path() . $savePath;

        Log::info('client postUpload  $fileName = ' . print_r($fileName,true));

        if ($file->validateFile() && $file->save($storage_path))
        {
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
                $imageUpload->save();

                $data['count'] = UploadUser::where('customer_id',$compacts['customer_id'])->count();

                if( $data['count'] > 0 ) {
                    $uploadusers = DB::table('uploadusers')
                        ->where('customer_id',$compacts['customer_id'])
                        ->whereNull('deleted_at')
                        ->first();
                    if($uploadusers->prime_flg <= 3) {
                        $prime_flg = 3;
                    } else {
                        $prime_flg = $uploadusers->prime_flg;
                    }
                    DB::table('uploadusers')
                        ->where('customer_id',$compacts['customer_id'])
                        ->whereNull('deleted_at')
                        ->update([
                            'yearmonth'  =>  $compacts['dateNew'],
                            'check_flg'  =>  2,
                            'prime_flg'  =>  $prime_flg,
                            'updated_at' =>  now()
                        ]);
                } else {
                    $uploaduser = new UploadUser();
                    $uploaduser->foldername      = $compacts['foldername'];
                    $uploaduser->business_name   = $compacts['business_name'];
                    $uploaduser->organization_id = $compacts['o_id'];
                    $uploaduser->customer_id     = $compacts['customer_id'];
                    $uploaduser->yearmonth       = $compacts['dateNew'];
                    $uploaduser->check_flg       = 2;
                    $uploaduser->prime_flg       = 3;
                    $uploaduser->save();
                }

                DB::commit();
                Log::info('beginTransaction - client postUpload saveFile end(commit)');

                // 重複レコードの確認・削除
                $duplicates = DB::select("
                    SELECT customer_id, COUNT(*) AS cnt
                    FROM uploadusers
                    WHERE deleted_at IS NULL
                    GROUP BY customer_id
                    HAVING cnt > 1
                ");
                foreach ($duplicates as $dup) {
                    logger("重複: customer_id={$dup->customer_id}, 件数={$dup->cnt}");
                }

                $toDelete = DB::select("
                    SELECT *
                    FROM uploadusers
                    WHERE id NOT IN (
                        SELECT * FROM (
                            SELECT MIN(id) AS id
                            FROM uploadusers
                            WHERE deleted_at IS NULL
                            GROUP BY customer_id
                        ) AS keep_ids
                    )
                    AND customer_id IN (
                        SELECT customer_id
                        FROM (
                            SELECT customer_id
                            FROM uploadusers
                            WHERE deleted_at IS NULL
                            GROUP BY customer_id
                            HAVING COUNT(*) > 1
                        ) AS dup_ids
                    )
                    AND deleted_at IS NULL
                ");
                foreach ($toDelete as $row) {
                    logger("削除対象: id={$row->id}, customer_id={$row->customer_id}, created_at={$row->created_at}");
                }

                DB::statement("
                    DELETE FROM uploadusers
                    WHERE id NOT IN (
                        SELECT * FROM (
                            SELECT MIN(id) AS id
                            FROM uploadusers
                            WHERE deleted_at IS NULL
                            GROUP BY customer_id
                        ) AS keep_ids
                    )
                    AND customer_id IN (
                        SELECT customer_id
                        FROM (
                            SELECT customer_id
                            FROM uploadusers
                            WHERE deleted_at IS NULL
                            GROUP BY customer_id
                            HAVING COUNT(*) > 1
                        ) AS dup_ids
                    )
                    AND deleted_at IS NULL
                ");
                Log::info('beginTransaction - client postUpload saveFile end(重複レコードの確認)');

            } catch(\QueryException $e) {
                Log::error('exception : ' . $e->getMessage());
                DB::rollback();
                Log::info('beginTransaction - client postUpload saveFile end(rollback)');
                $status = false;
                $this->json_put_status($status,$customer_id);
                $errormsg = 'アップロード出来ませんでした。';
                return \Response::json(['error'=>$errormsg,'status'=>'NG'], 400);
            }

            $status = false;
            $this->json_put_status($status,$customer_id);

            Log::info('client postUpload  END');
            return \Response::json(['error'=>'アップロードが正常に終了しました。','status'=>'OK'], 200);

        } else {
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
