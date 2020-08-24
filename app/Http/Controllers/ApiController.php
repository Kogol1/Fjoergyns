<?php

namespace App\Http\Controllers;

use App\VoteUser;
use App\Test;
use Carbon\Carbon;
use http\Env\Request;
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
}
