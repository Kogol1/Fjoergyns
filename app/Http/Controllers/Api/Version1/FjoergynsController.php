<?php

namespace App\Http\Controllers\Api\Version1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FjoergynsController extends Controller
{
    public function execute(Request $request)
    {
        //   dd(Hash::make('SD-S4SD:Fasd3?SFDPFasddSDF!SD'));
        if (!$request->has('_api-key', 'command') || !Hash::check(env('API_PASSWORD'), $request->get('_api-key'))) {
            return 'Unauthorized';
        }

        switch ($request->get('command')) {
            case 'start':
                shell_exec('screen -XS Fjoergyns quit');
                shell_exec('sh /home/pi/Desktop/Fjoergyns/start.sh');
            case 'kill':
                shell_exec('screen -XS Fjoergyns quit');
            default:
                dd('test');
        }
    }
}
