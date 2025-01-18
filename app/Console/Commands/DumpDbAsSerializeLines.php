<?php

namespace App\Console\Commands;
// use App\Console\BaseCommand;
use Illuminate\Console\Command;
// use DateTime;
// use Carbon\Carbon;
// use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
// public function connect(array $params): Doctrine\DBAL\Driver\Connection;
class DumpDbAsSerializeLines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DumpDbAsSerializeLines'; // コマンドの名前を設定

    protected $name      = 'dev:dump-db-as-serialize-lines';

    /**
     * The console command description. 
     *
     * @var string
     */
    protected $description = 'テーブル名.txt の名前でシリアライズされた各レコードの結果を出力。dev:restore-db-from-serialize-lines と対';

    
    public function handle(array $params): void
    {
        Log::info('schedule DumpDbAsSerializeLines START ');
        // $username ='a';
        // $password = 'null';
        // $driverOptions[] = 'null';
        // $params = arry($username,$password,$driverOptions);
        
        $dbal = Model::resolveConnection()->getDoctrineSchemaManager();
        // テーブル名を取得
        foreach ($dbal->listTableNames() as $tableName) {
            // 開始通知
            $this->info('start: '.$tableName);

            // 書き込み用のファイルを用意
            $fp = new \SplFileObject(storage_path('mysqlBackUp/'.$tableName.'.txt'), 'wb+');

            // chunk メソッドに orderBy が必須のためカラム名を取得
            $colNames = array_map(static fn (Column $col) => $col->getName(), $dbal->listTableColumns($tableName));
            // chunk メソッドを用いてメモリに対して優しい SELECT を繰り返す
            DB::query()
                ->select()->from($tableName)
                ->orderBy($colNames[array_key_first($colNames)])
                ->chunk(5000, static function (Collection $collection) use ($fp) {
                    // SELECT して得た各レコードを serialize してファイルに書き込み
                    // serialize を用いると null を始めとした様々な型を処理しやすい
                    $collection->each(static fn (object $stdObj) => $fp->fwrite(serialize($stdObj)."\n"));
                });

            // 終了通知
            $this->info('finish: '.$tableName);

            Log::info('schedule DumpDbAsSerializeLines END ');

            // 開いたファイルへの接続はファイルポインタが参照不能になった時点で PHP が自動で閉じてくれる
        }
    }
}
