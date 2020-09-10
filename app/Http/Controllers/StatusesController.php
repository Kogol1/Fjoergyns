<?php

namespace App\Http\Controllers;

use App\Status;


class StatusesController extends Controller
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
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function post()
    {
        if (!isset($_REQUEST['api-key']) || !$_REQUEST['api-key'] === env('API_KEY')){
            return response('Wrong or no api key', 401);
        }
        $status = new Status($_REQUEST);
        $status->save();
        return response('Data saved: '.$status->data, 200);
    }


}
