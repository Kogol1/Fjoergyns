<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanServer extends Model
{

    protected $table = 'plan_servers';
    protected $connection = 'mysql_plan';

    protected $fillable = [
        'name', 'uuid', 'server_uuid'
    ];


}
