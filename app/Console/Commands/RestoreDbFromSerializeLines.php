<?php

namespace App\Console\Commands;

// use App\Console\BaseCommand;
use Illuminate\Console\Command;
// use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RestoreDbFromSerializeLines extends Command
{
    /** 
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:RestoreDbFromSerializeLines'; // コマンドの名前を設定

    protected $name      = 'dev:restore-db-from-serialize-lines';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'テーブル名.txt の名前でシリアライズされた各レコードの結果を元に INSERT。dev:dump-db-as-serialize-lines と対';

    /**
     * @throws \Throwable
     */
    public function handle(): void
    {
        Log::info('schedule RestoreDbFromSerializeLines handle START ');

        // 外部キー制約を無視する様に設定変更
        DB::statement('set foreign_key_checks = 0;');
        try {
            // 半端に終わると再開が手間であったり、外部キー制約が壊れたりして手間なのでトランザクションを使用
            // INSERT すべきデータが巨大な場合、途中まで行った処理巻き戻しのペナルティの方が辛いので、どうにか途中から再開できる&途中まで巻き戻せる仕組みを作った方がいい
            DB::statement('BEGIN;');
            $this->main();
            DB::statement('COMMIT;');
        } catch (\Throwable $e) {
            DB::statement('ROLLBACK;');
            throw $e;
        } finally {
            // 外部キー制約を有効にする様に設定変更
            // try finally によって多少の異常事態ではまず実行される様に用意
            DB::statement('set foreign_key_checks = 1;');
        }
        Log::info('schedule RestoreDbFromSerializeLines handle END ');
    }

    /**
     * @return void
     */
    protected function main(): void
    {
        Log::info('schedule RestoreDbFromSerializeLines main START ');

        // Doctrine のテーブル接続ツールを使用
        $dbal = Model::resolveConnection()->getDoctrineSchemaManager();
        // テーブル名を取得
        foreach ($dbal->listTableNames() as $tableName) {
            // 開始通知
            $this->info('start: '.$tableName);
            // 処理を続行しないパターンを列挙
            if ($tableName === 'migrations') {
                $this->info('テーブル定義はしてあるものとし migrations を無視しました。');
                continue;
            }
            if (! file_exists(storage_path('mysqlBackUp/'.$tableName.'.txt'))) {
                $this->warn($tableName.' に対応するCSVファイルが見つかりませんでした。');
                continue;
            }
            // INSERT 用のファイルを用意
            $fp = new \SplFileObject(storage_path('mysqlBackUp/'.$tableName.'.txt'), 'rb+');

            $i            = 0; // ある程度の行ごとにまとめて INSERT する
            $recordStacks = []; // INSERT 待ちの行を保持しておく
            while ($line = $fp->fgets()) {
                if (empty($line)) {
                    continue; // 空行対策
                }
                // unserialize することで SELECT した時のオブジェクトそのままを復元
                // 第二引数の stdClass は不意に想定外のクラスを unserialize しないための安全弁
                // (array) キャストは insert メソッドを使うための都合
                $recordStacks[] = (array) unserialize($line, [\stdClass::class]);
                ++$i;
                if ($i > 5000) {// 行が貯まったら INSERT
                    DB::query()->from($tableName)->insert($recordStacks);
                    $recordStacks = []; // 次からまた貯めるために初期化
                    $i            = 0; // 次からまた貯めるために初期化
                }
            }
            // 5000貯まっていない最後のスタックを INSERT
            DB::query()->from($tableName)->insert($recordStacks);
            // 終了通知
            $this->info('finish: '.$tableName);

            Log::info('schedule RestoreDbFromSerializeLines main END ');
            // 開いたファイルへの接続はファイルポインタが参照不能になった時点で PHP が自動で閉じてくれる
        }
    }
}
