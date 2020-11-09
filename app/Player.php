<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    protected $table = 'players';

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

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getSumVotes($subDays = null)
    {
        if ($subDays === null){
            return $this->votes->count();
        }
        return $this->votes->whereBetween('created_at', [Carbon::now()->subDays($subDays), Carbon::now()])->count();
    }

    public static function rollWeeklyWinner()
    {
        $voters = self::whereHas('votes', function (Builder $query) {
            $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
        }, '>=', 7)->get();
        if (count($voters) === 0){
            return [];
        }
        $shuffledVoters = $voters->shuffle();
        $n = random_int(0, count($voters));
        $winner = $shuffledVoters->first()->name;
        if (env('APP_ENV') === 'production'){
            self::where('name', $winner)->first()->addPoints(25);
        }

        return [
            'winner' => $winner,
            'number' => $n + 1,
            'totalPlayers' => count($voters),
        ];
    }

    public function addPoints($points): void
    {
        $playerName = $this->name;
        shell_exec('sudo screen -S LogIn -p 0 -X stuff " tm add ' . $playerName . ' ' . $points . '\n";');
    }
}
