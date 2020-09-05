<?php

namespace App\Http\Controllers;

use App\CoreProtectCommand;
use App\CoreProtectCommandEco;
use App\CoreProtectUser;
use App\CoreProtectUserEco;
use App\PlanKills;
use App\PlanServer;
use App\PlanUser;
use App\VoteUser;
use App\Test;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\View\View;
use function MongoDB\BSON\toJSON;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $top int
     * @return array
     */
    public function getTopVoters($top): array
    {
        $topVoters = VoteUser::orderByDesc('MonthTotal')->take($top)->get();
        return [
            $topVoters->first(),
            $topVoters->get(2),
            $topVoters->get(3),
        ];
    }

    public function post()
    {
        $test = new Test;
        if (isset($_POST['arg0'])):
            $arg0 = $_POST['arg0'];
            $test->arg = json_encode($arg0);
$test->save();
        endif;
        return $_POST;

    }

    public function getCommands($name, $server): View
    {
        if ($server === 'survival'){
            $user = CoreProtectUser::where('user', $name)->first();
            if (!is_null($user))
            {
                $commands = CoreProtectCommand::where('user', $user->rowid)->orderByDesc('time')->take('3000')->get(['time', 'message']);
            }
        }
        if ($server === 'economy'){
            $user = CoreProtectUserEco::where('user', $name)->first();
            if (!is_null($user))
            {
                $commands = CoreProtectCommandEco::where('user', $user->rowid)->orderByDesc('time')->take('3000')->get(['time', 'message']);
            }
        }
        return view('user-commands')->with([
            'commands' => $commands ?? null,
            'user' => $user ?? null,
            'server' => $server,
        ]);
    }

    /**
     * @param $killer
     * @param $victim
     * @param $server
     * @return View|string
     */
    public function getTpaKills($killer, $victim, $server)
    {
        $planServer = PlanServer::where('name', ucfirst($server))->first();
        if ($planServer === null){
            return view('tpa-kills')->with([
                'log' => $log ?? null,
                'killer' => $killer ?? null,
                'victim' => $victim ?? null,
                'server' => $server,
            ]);
        }
        $planKiller = PlanUser::where('nickname',$killer)->where('server_uuid', $planServer->uuid)->first();
        $planVictim = PlanUser::where('nickname',$victim)->where('server_uuid', $planServer->uuid)->first();
        if ($planKiller === null || $planVictim === null){
            return view('tpa-kills')->with([
                'log' => $log ?? null,
                'killer' => $killer ?? null,
                'victim' => $victim ?? null,
                'server' => $server,
            ]);
        }
        $planDeaths = PlanKills::where('victim_uuid', $planVictim->uuid)->where('killer_uuid', $planKiller->uuid)->where('server_uuid', $planServer->uuid)->get();
        foreach ($planDeaths as $planDeath){
            $planDeath->time = round($planDeath->date/1000, 0,0);
        }
        if (ucfirst($server) === 'Survival'){
            $co_user1 = CoreProtectUser::where('user', $killer)->first();
            $co_user2 = CoreProtectUser::where('user', $victim)->first();
            if (!is_null($co_user1) && !is_null($co_user2))
            {
                $commands = CoreProtectCommand::orderByDesc('time')->where(function ($q) use($co_user1, $co_user2){
                    $q->where('user', $co_user1->rowid);
                    $q->orWhere('user', $co_user2->rowid);
                })->take(500)->get(['user', 'time', 'message']);
            }
            $log = $commands->mergeRecursive($planDeaths);
            $log = $log->sortByDesc('time');
        }
        if (ucfirst($server) === 'Economy'){
            $co_user1 = CoreProtectUserEco::where('user', $killer)->first();
            $co_user2 = CoreProtectUserEco::where('user', $victim)->first();
            if (!is_null($co_user1) && !is_null($co_user2))
            {
                $commands = CoreProtectCommandEco::orderByDesc('time')->where(function ($q) use($co_user1, $co_user2){
                    $q->where('user', $co_user1->rowid);
                    $q->orWhere('user', $co_user2->rowid);
                })->take(500)->get(['user', 'time', 'message']);
            }
            $log = $commands->mergeRecursive($planDeaths);
            $log = $log->sortByDesc('time');
        }
        return view('tpa-kills')->with([
            'log' => $log ?? null,
            'killer' => $killer ?? null,
            'victim' => $victim ?? null,
            'server' => $server,
        ]);
    }
}
