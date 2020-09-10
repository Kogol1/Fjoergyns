<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';
    protected $fillable = ['server_name', 'tps', 'players_online',];

    public static $servers = [
        'Survival',
        'Economy',
    ];

    public static function getServersToJson()
    {
        $data = [];
        foreach (self::$servers as $server){
            $serverStatus = self::where('server_name', $server)->orderByDesc('created_at')->first();
            $serverStatus->date = $serverStatus->created_at->format('Y-m-d H:i:s');
            $data[] = json_encode($serverStatus->only(['server_name', 'tps', 'players_online', 'date']));
        }
        return json_encode($data);
    }

}
