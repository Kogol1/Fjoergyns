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
        \App\Console\Commands\TransferVotes::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('czs:top-vote')->lastDayOfMonth('23:30');
        $schedule->command('czs:weekly-vote')->weeklyOn(6, '23:59');
        $schedule->command('czs:top-warns')->dailyAt('12:00');
        $schedule->command('czs:top-bans')->dailyAt('12:00');
    }
}
