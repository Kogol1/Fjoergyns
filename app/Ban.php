<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ban extends Model
{
    protected $table = 'litebans_bans';
    protected $connection = 'mysql_litebans';

    /**
     * @return BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(LiteBanPlayer::class, 'uuid', 'uuid');
    }


    /**
     * @return array
     */
    public static function countBansByAdmins(): array
    {
        $bans = [];
        foreach (Admin::where('active', true)->whereHas('role', function ($q) {
            $q->where('active_litebans', true);
        })->orderBy('role_id')->get() as $admin)
        {
            $bansCount = self::where('banned_by_name', $admin->name)->count();
            if (!$admin->aliases->isEmpty()) {
                foreach ($admin->aliases as $alias) {
                    $bansCount += self::where('banned_by_name', $alias->alias_name)->count();
                }
            }

            $bans[$admin->name] = $bansCount;
        }
        return $bans;
    }

    /**
     * @param $adminName
     * @return int
     */
    public static function countPermaBans($adminName): int
    {
        $admin = Admin::where('name', $adminName)->first();
        $bansCount = self::where('banned_by_name', $admin->name)->where('until', -1)->count();
        if (!$admin->aliases->isEmpty()) {
            foreach ($admin->aliases as $alias) {
                $bansCount += self::where('banned_by_name', $alias->alias_name)->where('until', -1)->count();
            }
        }
        return $bansCount ?? 0;
    }


    /**
     * @param $adminName
     * @param $hours int
     * @return int
     */
    public static function countBansInPeriod($adminName, $hours): int
    {
        $admin = Admin::where('name', $adminName)->first();
        $bansCount = self::where('banned_by_name', $admin->name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)) . '000')->count();
        if (!$admin->aliases->isEmpty()) {
            foreach ($admin->aliases as $alias) {
                $bansCount += self::where('banned_by_name', $alias->alias_name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)) . '000')->count();
            }
        }
        return $bansCount ?? 0;
    }

    /**
     * @param $adminName
     * @param $hours int
     * @return array
     */
    public static function countBansInPeriodDifferServers($adminName, $hours): array
    {
        $admin = Admin::where('name', $adminName)->first();
        $bansCountSurvival = self::where('banned_by_name', $admin->name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)) . '000')->where('server_origin', 'survival')->count();
        if (!$admin->aliases->isEmpty()) {
            foreach ($admin->aliases as $alias) {
                $bansCountSurvival += self::where('banned_by_name', $alias->alias_name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)) . '000')->where('server_origin', 'survival')->count();
            }
        }
        $bansCountEconomy = self::where('banned_by_name', $admin->name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)) . '000')->where('server_origin', 'economy')->count();
        if (!$admin->aliases->isEmpty()) {
            foreach ($admin->aliases as $alias) {
                $bansCountEconomy += self::where('banned_by_name', $alias->alias_name)->where('time', '>', strtotime(Carbon::now()->subHours($hours)) . '000')->where('server_origin', 'economy')->count();
            }
        }
        return ['survival' => $bansCountSurvival ?? 0, 'economy' => $bansCountEconomy ?? 0];
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

    public static function getPercentageOfPermaBans($admin): float
    {
        return round((self::countPermaBans($admin) / self::where('until', -1)->count()) * 100, 2);
    }
}
