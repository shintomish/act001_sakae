<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        Commands\FileTmpDelete::Class,
        Commands\File90Delete::Class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule->call(new FileTmpDelete($schedule))   // uploadfileのtmpを削除
        //             ->dailyAt('03:55');                 // 毎日AM3:55に実行する

        $schedule->command('command:FileTmpDelete')     // uploadfileのtmpを削除
                    ->dailyAt('03:55');                 // 毎日AM3:55に実行する

        $schedule->command('cache:clear')
                    ->dailyAt('04:00');                 // 毎日AM4:05に実行する
        $schedule->command('route:clear')
                    ->dailyAt('04:00');                 // 毎日AM4:10に実行する
        $schedule->command('config:clear')
                    ->dailyAt('04:00');                 // 毎日AM4:15に実行する
        $schedule->command('view:clear')
                    ->dailyAt('04:00');                 // 毎日AM4:20に実行する

        $schedule->command('backup:clean')              // 古いバックアップファイルを削除
                 ->dailyAt('04:55');                    // 毎日AM4:55に実行する
        $schedule->command('backup:run --only-db')      // DBのみのバックアップにはオプション「–only-db」を指定します。
                 ->dailyAt('05:00');                    // 毎日AM5:00に実行する

        // $schedule->call(new File90Delete($schedule))    // userdata配下の90日経過したファイルを削除
        $schedule->command('command:File90Delete')         // userdata配下の90日経過したファイルを削除
                 ->weeklyOn(0, '05:10');                // 毎週日曜日(0)AM5:10に実行する
                //  ->dailyAt('15:35');                 // 毎日AM4:20に実行する

        $schedule->command('backup:run')
                 ->weeklyOn(0, '06:10');                // 毎週日曜日(0)AM6:00に実行する

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
