<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{

    protected $table = 'votes';

    protected $fillable = [
        'name',
    ];

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
     * @param $month int
     * @return string
     */
    public static function localizeMonth($month): string
    {
        return self::$months[$month];
    }

    public static function getTopVoter($subDays)
    {
        return self::select('player_id')->whereBetween('created_at', [Carbon::now()->subDays($subDays), Carbon::now()])->groupBy('player_id')->orderByRaw('COUNT(*) DESC')->first()->player;
    }

    public static function getTopVoters(int $subDays, int $voters)
    {
        $topVoters = collect([]);
        $playerIds = self::select('player_id')
            ->groupBy('player_id')
            ->orderByRaw('COUNT(*) DESC')
            ->take($voters)
            ->pluck('player_id');
        foreach ($playerIds as $playerId){
            $topVoters->add(Player::find($playerId));
        }
        return $topVoters;
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

}
