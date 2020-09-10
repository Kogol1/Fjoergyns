<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';
    protected $fillable = ['server_name', 'tps', 'players_online',];

    public static function getServersToJson()
    {
        return;
    }

}
