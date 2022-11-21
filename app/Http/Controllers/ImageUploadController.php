<?php
namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\ImageUpload;
use App\Models\UploadUser;
use App\Models\Customer;

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

class ImageUploadController extends Controller
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

    public function create()
    {
        // return view('imageupload');
    }

    /** * Generate Upload View * * @return void */
    public function dropzoneUi() {
        return view('upload-view');
    }

    /** * File Upload Method V1.0 * * @return void */
    public function dropzoneFileUpload(Request $request) {

        Log::info('dropzoneFileUpload START');

        //-------------------------------------------------------------
        //- Request パラメータ
        //-------------------------------------------------------------
        $customer_id = $request->Input('customer_id');

        // ログインユーザーのユーザー情報Userを取得する
        // ログインユーザーのユーザー情報を取得する
        $user  = $this->auth_user_info();
        $u_id = $user->id;
        $organization_id =  $user->organization_id;


        // ログインユーザーのユーザー情報Customer(フォルダー名)を取得する
        $uploadusers     = $this->auth_user_foldername($customer_id);
        $foldername      = $uploadusers->foldername;
        $business_name   = $uploadusers->business_name;
        $folderpath      = 'userdata'. '/' . $foldername;

        // Log::debug('dropzoneFileUpload uploadusers = ' . print_r(json_decode($uploadusers),true));
        // var_dump($folderpath);

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        // $dateNew = ($now->format('Y-m-d H:i:s.u')); // E.g., string(26) "2016-05-20 19:36:26.900794"
        $dateNew = ($now->format('Y/m')); // E.g., string(26) "2016-05-20 19:36:26.900794"

        // var_dump($dateNew);
        // die;

        $image = $request->file('file');
        $fileName = $image->getClientOriginalName();        // FileName
        $fileSize = $image->getSize();                      // FileSize
        // $image->move(public_path($folderpath),$fileName);   // Folder ('userdata')
        $image->move(storage_path($folderpath),$fileName);   // Folder ('userdata')

        Log::debug('dropzoneFileUpload storage_path($folderpath) = ' . print_r(json_decode(storage_path($folderpath)),true));
        $imageUpload = new ImageUpload();
        $imageUpload->filename        = $fileName;
        $imageUpload->organization_id = $organization_id;
        $imageUpload->user_id         = $u_id;
        $imageUpload->customer_id     = $customer_id;
        $imageUpload->filesize        = $fileSize;
        $imageUpload->save();               //  Inserts

        $data['count'] = UploadUser::where('user_id',$u_id)->count();

        //更新
        if( $data['count'] > 0 ) {
            $uploadusers = DB::table('uploadusers')
            // 顧客の絞り込み
            ->where('customer_id',$customer_id)
            // 削除されていない
            ->whereNull('deleted_at')
            ->update([
                'yearmonth'  =>  $dateNew,
                'updated_at' =>  now()
            ]);
        //追加
        } else {
            $uploaduser = new UploadUser();
            $uploaduser->foldername      = $foldername;     // フォルダー000x
            $uploaduser->business_name   = $business_name;  // 顧客名
            $uploaduser->organization_id = $organization_id;
            $uploaduser->customer_id     = $customer_id;
            $uploaduser->yearmonth       = $dateNew;        // 年月 2021/08
            $uploaduser->save();                            // Inserts
        }

        Log::info('dropzoneFileUpload END');

        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.upload'));

        return response()->json(['success'=>$fileName]);
    }

}
