<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $table = 'servers';
    protected $connection = 'mysql_localhost';

    public static function getArrayForDiskUsage()
    {
        $servers = [];
        foreach (self::where('check_disk_usage', true)->get() as $server) {
            $servers[$server->name] = 0;
        }
        return $servers;
    }
}
