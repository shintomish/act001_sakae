<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeleteOldFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:userdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete files older than 120 days in storage/app/userdata';
    /**
     * Execute the console command.
     * 定期的に実行するには、app/Console/Kernel.php の schedule メソッドに以下を追加
     * $schedule->command('cleanup:userdata')->daily();
     * @return int
     */
    public function handle()
    {
        Log::info('Command DeleteOldFiles START');

        // return Command::SUCCESS;
        $path = storage_path('app/userdata/folder0003');    // app/userdata
        $files = glob($path . '/*');

        $now = Carbon::now();

        foreach ($files as $file) {
            if (is_file($file)) {
                $lastModified = Carbon::createFromTimestamp(filemtime($file));

                if ($lastModified->diffInDays($now) > 120) {
                    unlink($file);
                    $this->info("Deleted: $file");
                }
            }
        }
        $this->info('Cleanup completed.');
        Log::info('Command DeleteOldFiles END');
    }
}
