<?php

namespace App\Console\Commands;

use App\Ban;
use App\VoteUser;
use App\VoteUserEco;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UnlinkBannedPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:unlink-banned-players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $permaBans = Ban::where('time', '>', Carbon::now()->subDay()->timestamp . '000')->where('until', -1)->with('player')->get();
        dd($permaBans->first());
        foreach ($permaBans as $permaBan) {
            if (env('APP_LOCATION') === 'HETZNER-INTEL'){
                shell_exec('screen -S Survival -p 0 -X stuff " discordsrv unlink ' . $permaBan->player->name . '\n";');
            }
            if (env('APP_LOCATION') === 'HETZNER-AMD'){
                shell_exec('screen -S Economy -p 0 -X stuff " discordsrv unlink ' . $permaBan->player->name . '\n";');
            }
            sleep(1);
        }

    }
}
