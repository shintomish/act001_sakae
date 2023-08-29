<?php

namespace App\Console\Commands;

use DateTime;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \FilesystemIterator;

class File90Delete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:File90Delete'; // コマンドの名前を設定

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command File90Delete'; // コマンドの説明を設定

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // --- userdata配下の90日経過したファイルを削除 ----
        // Log::info('schedule File90Delete START');
        // Log::useFiles(storage_path().'/logs/actver-File90Delete-st'.__CLASS__.'-'.Carbon::now()->format('Y-m-d').'.log');

        // =================
        // 1日経過したアップロードファイルを削除
        // collect(\Storage::disk('local')->listContents('userdata', true))
        //     ->each(function($file) {
        //         if ($file['type'] == 'file' && $file['timestamp'] < now()->subDays(1)->getTimestamp()) {
        //             // dd( "{$file['path']} を削除する" , \Storage::disk('local')->path($file['path']) );
        // Log::debug('schedule File90Delete $file[path] = ' . print_r($file['path'] ,true));

        //             \Storage::disk('local')->delete($file['path']);
        //         }
        // });
        // =================

        // **************************
        // 90日経過したアップロードファイルを削除
        // ディレクトリが多階層になっていて、指定したディレクトリ以下の全てのファイルを
        // 再帰的に探索して削除するには次のようにします。
        date_default_timezone_set('Asia/Tokyo');

        //削除期限
        // $expire = strtotime("24 hours ago");    // 24 時間前より古いファイルが削除
        // $expire = strtotime("90 days ago");    // ★90 日前より古いファイルを削除★
        $expire = strtotime("120 days ago");    // ★120 日前より古いファイルを削除★ 2022/08/30

        //ディレクトリ
        $dir = storage_path('app/userdata/');
        // Log::debug('schedule File90Delete $dir = ' . print_r($dir ,true));

        $this->remove_old_files($dir, $expire);
        // **************************
        // Log::info('schedule File90Delete END');
        // Log::useFiles(storage_path().'/logs/actver-File90Delete-ed'.__CLASS__.'-'.Carbon::now()->format('Y-m-d').'.log');

        //  *    2023/01/03
        //  *    news_check()     : newsreposのcreated_atが30日以降なら
        //  *                       urgent_flg = 1 にする
        $this->news_check();

        return 0;
    }

    function remove_old_files($dir, $timestamp){
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dir,
                 FilesystemIterator::CURRENT_AS_FILEINFO    // 詳細なファイルの情報
                |FilesystemIterator::SKIP_DOTS              // 「.」「..」をスキップ
                |FilesystemIterator::KEY_AS_PATHNAME        // key() としてファイルパス＋ファイル名が得られる
            ), RecursiveIteratorIterator::LEAVES_ONLY       // 「葉のみ」、つまりファイルのみが取得
        );

        foreach($iterator as $pathname => $info){
            if($info->getMTime() < $timestamp){
                //chmod($pathname, 0666);
        // Log::info('schedule File90Delete remove_old_files $pathname = ' . print_r($pathname ,true));

                unlink($pathname);
            }
        }
    }

    /**
     *    2023/01/03
     *    news_check()     : newsreposのcreated_atが30日以降なら
     *                       urgent_flg = 1 にする
     *
     */
    function news_check() {

        // Log::info('schedule File90Delete news_check START');

        // 1ヶ月前
        $date = new Carbon(now());
        $old  = $date->subMonths(1);
        $str = ( new DateTime($old) )->format('Y-m-d');

        // Log::debug('schedule File90Delete news_check $str = ' . print_r($str ,true));

        try {
            $query = '';
            $query .= 'UPDATE ';
            $query .= 'newsrepos ';
            $query .= 'SET newsrepos.urgent_flg = 1 ';
            $query .= ', newsrepos.updated_at=NOW() ';
            $query .= 'WHERE ';
            $query .= 'deleted_at is NULL AND ';
            $query .= 'created_at < \'%str%\' AND ';
            $query .= 'newsrepos.urgent_flg = 2 ';
            $query  = str_replace('%str%',  $str, $query);
        // Log::debug('schedule File90Delete news_check $query = ' . print_r($query ,true));

            DB::update($query);
            DB::commit();
            // Log::info('beginTransaction - schedule File90Delete news_check end(commit)');
        }
        catch(\QueryException $e) {
            Log::error('exception : ' . $e->getMessage());
            DB::rollback();
            // Log::info('beginTransaction - schedule File90Delete news_check end(rollback)');
        }

        // Log::info('schedule File90Delete news_check END');
    }
}
