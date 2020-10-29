<?php

namespace App\Console\Commands;

use App\Admin;
use App\CoreProtectBlock;
use App\Role;
use App\TokenUser;
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
        $vote = VoteUser::where('Points', '>', 0)->whereNotNull('PlayerName')->get();
        foreach ($vote as $user){
            $tokenUser = new TokenUser();
            $tokenUser->name = $user->PlayerName;
            $tokenUser->tokens = $user->Points;
            $tokenUser->save();
            echo $tokenUser->name.' '.$tokenUser->tokens;
        }
    }
}
