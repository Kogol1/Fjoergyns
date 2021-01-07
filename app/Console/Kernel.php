<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\TopVoters::class,
        \App\Console\Commands\WeeklyVotes::class,
        \App\Console\Commands\TopWarns::class,
        \App\Console\Commands\TopBans::class,
        \App\Console\Commands\CreateRole::class,
        \App\Console\Commands\CreateAdmin::class,
        \App\Console\Commands\CreateAlias::class,
        \App\Console\Commands\PurgeOldData::class,
        \App\Console\Commands\UnlinkBannedPlayers::class,
        \App\Console\Commands\DiskUsage::class,
        \App\Console\Commands\DiskUsageCheck::class,
        \App\Console\Commands\VoteStats::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (env('APP_MAIN_INSTANCE') === true){
            $schedule->command('czs:top-vote')->lastDayOfMonth('23:30');
            $schedule->command('czs:weekly-vote')->weeklyOn(7, '23:30');
            $schedule->command('czs:top-warns')->dailyAt('12:00');
            $schedule->command('czs:top-bans')->dailyAt('12:00');
            $schedule->command('czs:vote-stats')->dailyAt('12:00');
            $schedule->command('czs:purge-database')->dailyAt('03:00');

        }
        $schedule->command('system:disk-usage')->dailyAt('12:00');
        $schedule->command('system:disk-usage-check')->everyFifteenMinutes();
        $schedule->command('czs:unlink-banned-players')->twiceDaily('03:00');
    }
}
