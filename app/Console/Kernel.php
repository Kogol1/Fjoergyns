<?php

namespace App\Console;

use App\Console\Commands\CreateAlias;
use App\Console\Commands\DiskUsage;
use App\Console\Commands\DiskUsageCheck;
use App\Console\Commands\PurgeOldData;
use App\Console\Commands\RecoveryStatus;
use App\Console\Commands\TopBans;
use App\Console\Commands\UnlinkBannedPlayers;
use App\Console\Commands\VoteStats;
use App\Console\Commands\WeeklyVotes;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        WeeklyVotes::class,
        TopBans::class,
        CreateAlias::class,
        PurgeOldData::class,
        UnlinkBannedPlayers::class,
        DiskUsage::class,
        DiskUsageCheck::class,
        VoteStats::class,
        RecoveryStatus::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        if (env('APP_MAIN_INSTANCE') === true){
            $schedule->command('czs:top-warns')->dailyAt('12:00');
            $schedule->command('czs:top-bans')->dailyAt('12:00');
            $schedule->command('czs:vote-stats')->dailyAt('12:00');
            $schedule->command('czs:purge-database')->dailyAt('03:00');
        }
        $schedule->command('system:disk-usage')->dailyAt('12:00');
        $schedule->command('system:disk-usage-check')->everyFifteenMinutes();
    }
}
