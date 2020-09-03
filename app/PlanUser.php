<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanUser extends Model
{

    protected $table = 'plan_nicknames';
    protected $connection = 'mysql_plan';

    protected $fillable = [
        'uuid', 'nickname', 'server_uuid',
    ];


}
