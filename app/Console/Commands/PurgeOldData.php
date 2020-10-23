<?php

namespace App\Console\Commands;

use App\Admin;
use App\CoreProtectBlock;
use App\Role;
use App\VoteUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:purge-database';

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
        $time = strtotime(Carbon::now()->subDays(15));
        DB::connection('mysql_coreprotect')->statement('DELETE FROM `CoreProtectblock` WHERE time < '.$time.' ;');
        dd(CoreProtectBlock::where('time', '<', $time)->count());
    }
}
