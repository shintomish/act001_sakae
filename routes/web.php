<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'web'], function () {
    //
    Route::auth();
    // 2022/10/17 ブラウザの戻る対応 UserController -> HomeController
    Route::get('/home', 'App\Http\Controllers\HomeController@index');
    // 2022/10/26 ログイン済み対応
    Route::get('/back', 'App\Http\Controllers\BackController@index');
});

// Log出力 views/log
Route::get('log/log', 'App\Http\Controllers\LogController@log');

//-----------------------------------------------------------------------------------------------
//- Alert
//-----------------------------------------------------------------------------------------------
// Route::get('alert',  'App\View\Components\Alert@render')    -> name('alertindex');
// Route::resource('alert',  'App\View\Components\Alert');

//-----------------------------------------------------------------------------------------------
//- Actlog
//-----------------------------------------------------------------------------------------------
Route::get('actlog',          'App\Http\Controllers\ActlogController@index')    -> name('actlogindex');
Route::post('actlog/download', 'App\Http\Controllers\ActlogController@download') -> name('actlogdownload');

//-----------------------------------------------------------------------------------------------
//- Chat
//-----------------------------------------------------------------------------------------------
Route::get('chat',         'App\Http\Controllers\ChatController@index')->name('chatin');
Route::get('ajax/chatin',  'App\Http\Controllers\Ajax\ChatController@index')->name('ajaxchatin'); // メッセージ一覧を取得
Route::post('ajax/chatcr', 'App\Http\Controllers\Ajax\ChatController@create')->name('ajaxchatcr'); // チャット登録

//-----------------------------------------------------------------------------------------------
//- ChatClient
//-----------------------------------------------------------------------------------------------
Route::get('chatclient',         'App\Http\Controllers\ChatClientController@index')->name('chatclientin');
Route::get('chatclient/serch',   'App\Http\Controllers\ChatClientController@serch')->name('chatclientserch'); // メッセージ一覧を取得
Route::get('ajax/chatclientin',  'App\Http\Controllers\Ajax\ChatClientController@index')->name('ajaxchatclientin'); // メッセージ一覧を取得
Route::post('ajax/chatclientcr', 'App\Http\Controllers\Ajax\ChatClientController@create')->name('ajaxchatclientcr'); // チャット登録

//-----------------------------------------------------------------------------------------------
//- TopClient クライアントTOP
//-----------------------------------------------------------------------------------------------
Route::get('topclient/index',  'App\Http\Controllers\TopClientController@index')->name('topclient');
Route::get('topclientserch',   'App\Http\Controllers\TopClientController@serch')->name('topclientserch');
Route::get('topclientpdf',     'App\Http\Controllers\TopClientController@show')->name('topclientpdf');
Route::get('topclinewpdf',     'App\Http\Controllers\TopClientController@show_new')->name('topclinewpdf');
Route::get('topclinewzip',     'App\Http\Controllers\TopClientController@show_houjin')->name('topclinewzip');
Route::get('topclient/alert/{id}',  'App\Http\Controllers\TopClientController@show_alert')->name('topclialert');
Route::get('topcli_pdf01',     'App\Http\Controllers\TopClientController@show_up01')->name('topcli_pdf01');
Route::get('topcli_pdf02',     'App\Http\Controllers\TopClientController@show_up02')->name('topcli_pdf02');
Route::get('topcli_pdf03',     'App\Http\Controllers\TopClientController@show_up03')->name('topcli_pdf03');

//-----------------------------------------------------------------------------------------------
//- Camera Capture
//-----------------------------------------------------------------------------------------------
// Route::get('camera_capture', [\App\Http\Controllers\CameraCaptureController::class, 'create'])->name('camera_capture');

//-----------------------------------------------------------------------------------------------
//- FileUpload クライアント アップロード Flow
//-----------------------------------------------------------------------------------------------
Route::post('topclient/upload/{id}','App\Http\Controllers\UploaderController@postUpload')->name('postUpload');

//-----------------------------------------------------------------------------------------------
//- TransHistory クライアント 送信履歴
//-----------------------------------------------------------------------------------------------
Route::get('transhistory',        'App\Http\Controllers\TransHistoryController@index')->name('transhistory');
Route::get('transserch',          'App\Http\Controllers\TransHistoryController@serch')->name('transserch');
// Route::get('transserch_custom',   'App\Http\Controllers\TransHistoryController@serch_custom')->name('transserch_custom');

//-----------------------------------------------------------------------------------------------
//- InvoiceHistory 請求書 送信履歴
//-----------------------------------------------------------------------------------------------
Route::get('invoicehistory',             'App\Http\Controllers\InvoiceHistoryController@index')->name('invoicehistory');
Route::get('invoicehistoryserch',        'App\Http\Controllers\InvoiceHistoryController@serch')->name('invoicehistoryserch');
Route::get('invoicehistory/pdf/{id}',    'App\Http\Controllers\InvoiceHistoryController@show_up01')->name('invoice_pdf01');
Route::get('invoicehistorymore',         'App\Http\Controllers\InvoiceHistoryController@more')->name('invoicehistory.more');
Route::post('invoicehistory/update_api', 'App\Http\Controllers\InvoiceHistoryController@update_api')->name('invoicehistory.update_api');

//-----------------------------------------------------------------------------------------------
//- top 事務所 00_3
//-----------------------------------------------------------------------------------------------
Route::get('top/index',        'App\Http\Controllers\TopController@index')->name('top');
Route::post('top/update_api',  'App\Http\Controllers\TopController@update_api')->name('top.update_api');
Route::resource('top',         'App\Http\Controllers\TopController');

//-----------------------------------------------------------------------------------------------
//- 事務所 organization 組織
//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
Route::resource('organization', 'App\Http\Controllers\OrganizationController');

//-----------------------------------------------------------------------------------------------
//- 事務所 Operation ログイン情報 00_ope 2023/09/04
//-----------------------------------------------------------------------------------------------
Route::get('operationperserch', 'App\Http\Controllers\OperationController@periodsearch')->name('operationperiodsearch');
Route::get('operationserch', 'App\Http\Controllers\OperationController@serch')->name('operationserch');
Route::get('operation/index', 'App\Http\Controllers\OperationController@index')->name('operationindex');
Route::resource('operation',  'App\Http\Controllers\OperationController');

//-----------------------------------------------------------------------------------------------
//- 事務所 user 利用ユーザー 00_1
//-----------------------------------------------------------------------------------------------
Route::get('userserch', 'App\Http\Controllers\UserController@serch')->name('userserch');
Route::resource('user', 'App\Http\Controllers\UserController');

//-----------------------------------------------------------------------------------------------
//- 事務所 customer 顧客 00_2
//-----------------------------------------------------------------------------------------------
Route::get('customerserch',     'App\Http\Controllers\CustomerController@serch' )->name('customerserch');
Route::get('customer/index',    'App\Http\Controllers\CustomerController@index' )->name('customer');
Route::post('customer/index',   'App\Http\Controllers\CsvImportController@store')->name('csvimprt.store');
Route::resource('customer',     'App\Http\Controllers\CustomerController');

//-----------------------------------------------------------------------------------------------
//- 事務所 controluser 複数法人 00_4
//-----------------------------------------------------------------------------------------------
Route::get('ctluserserch',    'App\Http\Controllers\ControlUserController@serch')->name('ctluserserch');
Route::get('ctluser/index',   'App\Http\Controllers\ControlUserController@index')->name('ctluserindex');
Route::resource('ctluser',    'App\Http\Controllers\ControlUserController');

//-----------------------------------------------------------------------------------------------
//- 事務所 UploadUser アップロードユーザー 01
//-----------------------------------------------------------------------------------------------
Route::get('uploaduser',           'App\Http\Controllers\UploadUserController@index')->name('uploaduser');
Route::get('uploadserch',          'App\Http\Controllers\UploadUserController@serch')->name('uploadserch');
Route::get('uploadserch_customer', 'App\Http\Controllers\UploadUserController@serch_customer')->name('uploadserch_customer');
Route::post('upload_check',        'App\Http\Controllers\UploadUserController@file_check')->name('upload_file_check');
Route::post('upload_custum',        'App\Http\Controllers\UploadUserController@custum')->name('upload_custum');
Route::post('uploaduser/update_api',  'App\Http\Controllers\UploadUserController@update_api')->name('uploaduser.update_api');

//-----------------------------------------------------------------------------------------------
//- 事務所 Filemaneger
//-----------------------------------------------------------------------------------------------
// Route::get('filemng', 'App\Http\Controllers\FilemngController@index')->name('filemng');
// Route::post('filemngsend', 'App\Http\Controllers\FilemngController@index')->name('filemngsend');
// Route::get('filemngshow', 'App\Http\Controllers\FilemngController@show')->name('filemngshow');
// Route::get('filemngpost', 'App\Http\Controllers\FilemngController@post')->name('filemngpost');
Route::get('filemng/index',  'App\Http\Controllers\FilemngController@index')->name('filemngindex');
Route::get('filemngdown',    'App\Http\Controllers\FilemngController@alldwonload')->name('filemngdown');
Route::get('filemngdell',    'App\Http\Controllers\FilemngController@alldelete')->name('filemngdell');
Route::post('filemngpost',   'App\Http\Controllers\FilemngController@post')->name('filemngpost');
Route::post('filemngupdate',   'App\Http\Controllers\FilemngController@update_api')->name('filemngupdate');
Route::resource('filemng',   'App\Http\Controllers\FilemngController');

//-----------------------------------------------------------------------------------------------
//- 事務所 newsrepo Nes作成 02
//-----------------------------------------------------------------------------------------------
Route::resource('newsrepo',   'App\Http\Controllers\NewsrepoController');
// Route::post('newsrepo/send',  'App\Http\Controllers\NewsrepoController@send')->name('newssend');
Route::post('newsrepo/sendmail',  'App\Http\Controllers\NewsrepoController@sendmail')->name('newsmail');
Route::post('newsrepo/temp',  'App\Http\Controllers\NewsrepoController@temp_serch')->name('newstemp');
// Route::post('newsrepo/non',  'App\Http\Controllers\NewsrepoController@non_serch')->name('newsnon');

//-----------------------------------------------------------------------------------------------
//- 事務所 spedelidate 納期特例 03
//-----------------------------------------------------------------------------------------------
Route::get('spedelidateserch',         'App\Http\Controllers\SpedelidateController@serch')->name('spedelidateserch');
Route::get('spedelidate_custom',       'App\Http\Controllers\SpedelidateController@serch_custom')->name('spedelidate_custom');
Route::get('spedelidate/input',        'App\Http\Controllers\SpedelidateController@input')->name('spedelidate.input');
Route::post('spedelidate/update_api',  'App\Http\Controllers\SpedelidateController@update_api')->name('spedelidate.update_api');
Route::resource('spedelidate',         'App\Http\Controllers\SpedelidateController');

//-----------------------------------------------------------------------------------------------
//- 事務所 yrendadjust 年末調整 04
//-----------------------------------------------------------------------------------------------
Route::get('yrendadjustserch',         'App\Http\Controllers\YrendadjustController@serch')->name('yrendadjustserch');
Route::get('yrendadjust_custom',       'App\Http\Controllers\YrendadjustController@serch_custom')->name('yrendadjust_custom');
Route::get('yrendadjust/input',        'App\Http\Controllers\YrendadjustController@input')->name('yrendadjust.input');
Route::post('yrendadjust/update_api',  'App\Http\Controllers\YrendadjustController@update_api')->name('yrendadjust.update_api');
Route::resource('yrendadjust',         'App\Http\Controllers\YrendadjustController');

//-----------------------------------------------------------------------------------------------
//- 事務所 notaccount 4ケ月以上会計処理をしていない事業者 05
//-----------------------------------------------------------------------------------------------
Route::get('notaccounth_custom', 'App\Http\Controllers\NotaccountController@serch_custom')->name('notaccounth_custom');
Route::resource('notaccount',    'App\Http\Controllers\NotaccountController');

//-----------------------------------------------------------------------------------------------
//- 事務所 advisorsfee 顧問料 06
//-----------------------------------------------------------------------------------------------
Route::get('advisorsfeeserch',         'App\Http\Controllers\AdvisorsfeeController@serch')->name('advisorsfeeserch');
Route::get('advisorsfee_custom',       'App\Http\Controllers\AdvisorsfeeController@serch_custom')->name('advisorsfee_custom');
Route::get('advisorsfee/input',        'App\Http\Controllers\AdvisorsfeeController@input')->name('advisorsfee.input');
Route::post('advisorsfee/update_api',  'App\Http\Controllers\AdvisorsfeeController@update_api')->name('advisorsfee.update_api');
Route::resource('advisorsfee',         'App\Http\Controllers\AdvisorsfeeController');

//-----------------------------------------------------------------------------------------------
//- 事務所 invoice 請求書 送信
//-----------------------------------------------------------------------------------------------
Route::get('invoice/index',        'App\Http\Controllers\InvoiceController@index')->name('invoicein');
Route::get('invoice/custom',       'App\Http\Controllers\InvoiceController@serch_custom')->name('invoice_custom');
Route::get('invoice/serch',        'App\Http\Controllers\InvoiceController@serch')->name('invoiceserch');
Route::post('invoice/upload/{id}', 'App\Http\Controllers\InvoiceController@postUpload')->name('invoicepostUpload');
Route::resource('invoice',         'App\Http\Controllers\InvoiceController');

//-----------------------------------------------------------------------------------------------
//- 事務所 invoiceconfirm 請求書 確認
//-----------------------------------------------------------------------------------------------
Route::get('invoiceconfirm/index',        'App\Http\Controllers\InvoiceconfirmController@index')->name('invoiceconfirmin');
Route::get('invoiceconfirm/custom',       'App\Http\Controllers\InvoiceconfirmController@serch_custom')->name('invoiceconfirm_custom');
Route::get('invoiceconfirm/serch',        'App\Http\Controllers\InvoiceconfirmController@serch')->name('invoiceconfirmserch');
Route::post('invoiceconfirm/upload/{id}', 'App\Http\Controllers\InvoiceconfirmController@postUpload')->name('invoiceconfirmpostUpload');
Route::resource('invoiceconfirm',         'App\Http\Controllers\InvoiceconfirmController');

//-----------------------------------------------------------------------------------------------
//- 事務所 wokprocbook 税理士業務処理簿 07
//-----------------------------------------------------------------------------------------------
Route::get('wokprocbook/index',        'App\Http\Controllers\WokprocbookController@index')->name('wokprocbookindex');
Route::get('wokprocbook/input',        'App\Http\Controllers\WokprocbookController@input')->name('wokprocbook.input');
Route::get('wokprocbookserch',         'App\Http\Controllers\WokprocbookController@serch')->name('wokprocbookserch');
Route::get('wokprocbook_custom',       'App\Http\Controllers\WokprocbookController@serch_custom')->name('wokprocbook_custom');
Route::get('wokprocbook/export',       'App\Http\Controllers\WokprocbookController@export')->name('wokprocbookexport');
Route::post('wokprocbook/update_api',  'App\Http\Controllers\WokprocbookController@update_api')->name('wokprocbook.update_api');
Route::resource('wokprocbook',         'App\Http\Controllers\WokprocbookController');

//-----------------------------------------------------------------------------------------------
//- 事務所 businesname 業務名 08
//-----------------------------------------------------------------------------------------------
Route::get('businesnameserch',   'App\Http\Controllers\BusinesnameController@serch')->name('businesnameserch');
Route::get('businesname_custom', 'App\Http\Controllers\BusinesnameController@serch_custom')->name('businesname_custom');
Route::post('businesname_cusid',  'App\Http\Controllers\BusinesnameController@serch_cus_id')->name('businesname_cus_id');
Route::resource('businesname',   'App\Http\Controllers\BusinesnameController');

//-----------------------------------------------------------------------------------------------
//- 事務所 progrecheck 進捗チェック 09
//-----------------------------------------------------------------------------------------------
Route::get('progrecheck/index',        'App\Http\Controllers\ProgrecheckController@index')->name('progrecheckindex');
Route::get('progrecheck/input',        'App\Http\Controllers\ProgrecheckController@input')->name('progrecheck.input');
Route::get('progrecheckserch',         'App\Http\Controllers\ProgrecheckController@serch')->name('progrecheckserch');
Route::get('progreserch_custom',       'App\Http\Controllers\ProgrecheckController@serch_custom')->name('progreserch_custom');
Route::get('progreserch_input',        'App\Http\Controllers\ProgrecheckController@serch_input')->name('progreserch_input');
Route::post('progrecheck/update_api',  'App\Http\Controllers\ProgrecheckController@update_api')->name('progrecheck.update_api');
Route::resource('progrecheck',         'App\Http\Controllers\ProgrecheckController');

//-----------------------------------------------------------------------------------------------
//- 事務所 schedule スケジュール 10
//-----------------------------------------------------------------------------------------------
Route::get('scheduleserch',        'App\Http\Controllers\ScheduleController@serch')->name('scheduleserch');
Route::get('scheduleserch_custom', 'App\Http\Controllers\ScheduleController@serch_custom')->name('schedule_custom');
Route::resource('schedule',        'App\Http\Controllers\ScheduleController');

//-----------------------------------------------------------------------------------------------
//- 事務所  新規設立 11
//-----------------------------------------------------------------------------------------------
Route::get('applestablserch',   'App\Http\Controllers\ApplestablController@serch')->name('applestablserch');
Route::resource('applestabl',   'App\Http\Controllers\ApplestablController');

//-----------------------------------------------------------------------------------------------
//- 事務所  年度更新 12
//-----------------------------------------------------------------------------------------------
Route::get('annualupdate/edit',   'App\Http\Controllers\AnnualupdateController@edit')->name('annualupdateedit');
Route::get('annualupdate/update', 'App\Http\Controllers\AnnualupdateController@update')->name('annualupdateupdate');

//-----------------------------------------------------------------------------------------------
//- 404 error
//-----------------------------------------------------------------------------------------------
//Route::post('support/post', 'App\Http\Controllers\SupportController@post')->name('support');


?>
