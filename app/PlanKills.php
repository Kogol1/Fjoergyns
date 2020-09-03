<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanKills extends Model
{

    protected $table = 'plan_kills';
    protected $connection = 'mysql_plan';

    protected $fillable = [
        'uuid', 'killer_uuid', 'server_uuid', 'victim_uuid'
    ];


}
