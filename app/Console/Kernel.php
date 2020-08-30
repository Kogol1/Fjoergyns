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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('czs:top-vote')->dailyAt('22:30');
        $schedule->command('czs:weekly-vote')->dailyAt('21:59');
        $schedule->command('czs:top-warns')->dailyAt('10:00');
        $schedule->command('czs:top-bans')->dailyAt('10:00');
    }
}
