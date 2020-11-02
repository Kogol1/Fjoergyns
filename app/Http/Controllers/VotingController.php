<?php

namespace App\Http\Controllers;

use App\CoreProtectCommand;
use App\CoreProtectCommandEco;
use App\CoreProtectUser;
use App\CoreProtectUserEco;
use App\PlanKills;
use App\PlanServer;
use App\PlanUser;
use App\Status;
use App\Vote;
use App\VoteUser;
use App\Test;
use Illuminate\Http\Response;

class VotingController extends Controller
{
    /**
     * @return Response
     */
    public function addVote(): Response
    {
        if (!isset($_REQUEST['api-key']) || !$_REQUEST['api-key'] === env('API_KEY')){
            return response('Wrong or no api key', 401);
        }
        $vote = new Vote([
            'name' => $_REQUEST['player_name'],
        ]);
        $vote->save();
        return response('OK', 200);
    }
}
