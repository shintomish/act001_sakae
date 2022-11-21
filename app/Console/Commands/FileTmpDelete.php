<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Support\Facades\Log;

class FileTmpDelete extends Command
{
// class FileTmpDelete
// {
//     public function __invoke() {
//         // echo '__invoke() が呼ばれました';

//         // uploadfileのtmpを削除
//         $file = new Filesystem;
//         $file->cleanDirectory('storage/tmp');
//     }
// }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:FileTmpDelete'; // コマンドの名前を設定

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command FileTmpDelete';

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
        // Log::info('schedule FileTmpDelete START');
        // ここに自動化したい処理を書く。
        // uploadfileのtmpを削除
        // 2022/11/05 一旦中止 顧客がuploadする際に /tmpにcustomer_idとjsonを作成
        // なくなると jsonファイルがないerror(crhom検証で)
        // $dirpath = storage_path() . "/tmp";
        // $file = new Filesystem;
        // // $file->cleanDirectory('storage/tmp');
        // $file->cleanDirectory($dirpath);
        // // Log::info('schedule FileTmpDelete END');

        return 0;
    }
}
