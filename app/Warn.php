<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Warn extends Model
{
    protected $table = 'litebans_warnings';
    protected $connection = 'mysql_litebans';

    protected $primaryKey = 'id';

    /**
     * @return array
     */
    public static function countWarnsByAdmins(): array
    {
        $warns = [];
        foreach (Admin::where('active', true)->whereHas('role', function ($q) {
            $q->where('active_litebans', true);
        })->orderBy('role_id')->get() as $admin)
        {
            $warnsCount = self::where('banned_by_name', $admin->name)->count();
            if (!$admin->aliases->isEmpty()){
                foreach ($admin->aliases as $alias){
                    $warnsCount += self::where('banned_by_name', $alias->alias_name)->count();
                }
            }

            $warns[$admin->name] =$warnsCount;
        }
        return $warns;
    }

    /**
     * @param $adminName
     * @param $hours int
     * @return int
     */
    public static function countWarnsInPeriod($adminName, $hours): int
    {
        $admin = Admin::where('name', $adminName)->first();
        $warnsCount = self::where('banned_by_name', $admin->name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->count();
        if (!$admin->aliases->isEmpty()){
            foreach ($admin->aliases as $alias){
                $warnsCount += self::where('banned_by_name', $alias->alias_name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->count();
            }
        }
        return $warnsCount ?? 0;
    }

    /**
     * @param $adminName
     * @param $hours int
     * @return int
     */
    public static function countWarnsInPeriodWithoutAdmin($hours): int
    {
        $warnsCount = self::where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->count();
        return $warnsCount ?? 0;
    }

    /**
     * @param $adminName
     * @param $hours int
     * @return array
     */
    public static function countWarnsInPeriodDifferServers($adminName, $hours): array
    {
        $admin = Admin::where('name', $adminName)->first();
        $warnsCountSurvival = self::where('banned_by_name', $admin->name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->where('server_origin', 'survival')->count();
        if (!$admin->aliases->isEmpty()){
            foreach ($admin->aliases as $alias){
                $warnsCountSurvival += self::where('banned_by_name', $alias->alias_name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->where('server_origin', 'survival')->count();
            }
        }
        $warnsCountEconomy = self::where('banned_by_name', $admin->name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->where('server_origin', 'economy')->count();
        if (!$admin->aliases->isEmpty()){
            foreach ($admin->aliases as $alias){
                $warnsCountEconomy += self::where('banned_by_name', $alias->alias_name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->where('server_origin', 'economy')->count();
            }
        }
        return ['survival' => $warnsCountSurvival ?? 0, 'economy' => $warnsCountEconomy ?? 0];
    }
}
