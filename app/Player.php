<?php

namespace App;

use Carbon\Carbon;
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

}
