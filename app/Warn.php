<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Warn extends Model
{

    protected $table = 'litebans_warnings';

    protected $primaryKey = 'id';

    public static $months = [
        1 => 'Leden',
        2 => 'Únor',
        3 => 'Březen',
        4 => 'Duben',
        5 => 'Květen',
        6 => 'Červen',
        7 => 'Červenec',
        8 => 'Srpen',
        9 => 'Září',
        10 => 'Říjen',
        11 => 'Listopad',
        12 => 'Prosinec',
    ];

    /**
     * @return array
     */
    public static function countWarnsByAdmins(): array
    {
        $warns = [];
        foreach (Admin::$admins as $admin)
        {
            $warnsCount = self::where('banned_by_name', $admin)->count();
            if ($admin === '_TotoWolff_'){
                $warnsCount += self::where('banned_by_name', 'Totowolff')->count();
            }
            $warns[$admin] =$warnsCount;
        }
        return $warns;
    }

    /**
     * @param $admin string
     * @param $hours int
     * @return int
     */
    public static function countWarnsInPeriod($admin, $hours): int
    {
            $warnsCount = self::where('banned_by_name', $admin)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->count();
            if ($admin === '_TotoWolff_'){
                $warnsCount += self::where('banned_by_name', 'Totowolff')->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->count();
            }
        return $warnsCount ?? 0;
    }

    /**
     * @param $admin string
     * @param $hours int
     * @return int
     */
    public static function countWarnsInPeriodDifferServers($admin, $hours): array
    {
        $warnsCountSurvival = self::where('banned_by_name', $admin)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->where('server_origin', 'survival')->count();
        if ($admin === '_TotoWolff_'){
            $warnsCountSurvival += self::where('banned_by_name', 'Totowolff')->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->where('server_origin', 'survival')->count();
        }
        $warnsCountEconomy = self::where('banned_by_name', $admin)->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->where('server_origin', 'economy')->count();
        if ($admin === '_TotoWolff_'){
            $warnsCountEconomy += self::where('banned_by_name', 'Totowolff')->where('time', '>', strtotime(Carbon::now()->subHours($hours)).'000')->where('server_origin', 'economy')->count();
        }
        return ['survival' => $warnsCountSurvival ?? 0, 'economy' => $warnsCountEconomy ?? 0];
    }
}
