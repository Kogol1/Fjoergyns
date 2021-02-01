<?php

namespace App\Console;

use App\Console\Commands\CreateAdmin;
use App\Console\Commands\CreateAlias;
use App\Console\Commands\CreateRole;
use App\Console\Commands\DiskUsage;
use App\Console\Commands\DiskUsageCheck;
use App\Console\Commands\PurgeOldData;
use App\Console\Commands\TopBans;
use App\Console\Commands\TopVoters;
use App\Console\Commands\TopWarns;
use App\Console\Commands\UnlinkBannedPlayers;
use App\Console\Commands\VoteStats;
use App\Console\Commands\WeeklyVotes;
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
        TopVoters::class,
        WeeklyVotes::class,
        TopWarns::class,
        TopBans::class,
        CreateRole::class,
        CreateAdmin::class,
        CreateAlias::class,
        PurgeOldData::class,
        UnlinkBannedPlayers::class,
        DiskUsage::class,
        DiskUsageCheck::class,
        VoteStats::class,
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
            $schedule->command('czs:top-warns')->dailyAt('12:00');
            $schedule->command('czs:top-bans')->dailyAt('12:00');
            $schedule->command('czs:vote-stats')->dailyAt('12:00');
           // $schedule->command('czs:purge-database')->dailyAt('03:00');
        }
        $schedule->command('system:disk-usage')->everyMinute();
        $schedule->command('system:disk-usage-check')->everyFifteenMinutes();
        $schedule->command('czs:unlink-banned-players')->twiceDaily('03:00');
    }
}
