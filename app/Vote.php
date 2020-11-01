<?php

namespace App;

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


}
