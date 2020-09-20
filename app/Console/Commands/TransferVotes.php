<?php

namespace App\Console\Commands;

use App\Admin;
use App\CoreProtectBlock;
use App\Role;
use App\VoteUser;
use App\VoteUserEco;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TransferVotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:transfer-votes';

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
        $voteEco = VoteUserEco::where('AllTimeTotal', '>', 0)->whereNotNull('PlayerName')->get();
        foreach ($voteEco as $economyUser){
            $survivalUser = VoteUser::where('PlayerName', $economyUser->PlayerName)->first();
            if ($survivalUser !== null){

                if ($economyUser->AllTimeTotal > $survivalUser->AllTimeTotal){
                    $survivalUser->AllTimeTotal = $economyUser->AllTimeTotal;
                }
                if ($economyUser->Point > $survivalUser->Point){
                    $survivalUser->Point = $economyUser->Point;
                }
                if ($economyUser->WeeklyTotal > $survivalUser->WeeklyTotal){
                    $survivalUser->WeeklyTotal = $economyUser->WeeklyTotal;
                }
                if ($economyUser->MonthTotal > $survivalUser->MonthTotal){
                    $survivalUser->MonthTotal = $economyUser->MonthTotal;
                }
                $survivalUser->save();
            }
        }
    }
}