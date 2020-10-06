<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteUser extends Model
{

    protected $table = 'VotingPlugin_Users';
    protected $connection = 'mysql_vote';
    public $timestamps = false;
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'PlayerName', 'AllTimeTotal', 'MonthTotal',
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

    /**
     * @param $top int
     * @return array
     */
    public static function getTopVoters($top): array
    {
        $topVoters = self::orderByDesc('MonthTotal')->whereNull('TopVoterIgnore')->take($top)->get();
        $returnArray = [];
        foreach ($topVoters as $topVoter){
            $returnArray[] = $topVoter;
        }
        return $returnArray;
    }

    /**
     * @return int
     */
    public static function getWeeklyVotesCount(): int
    {
        return self::where('WeeklyTotal', '>', 0)->pluck('WeeklyTotal')->sum();
    }

    /**
     * @return int
     */
    public static function getWeeklyVoteUsersCount(): int
    {
        return self::where('WeeklyTotal', '>', 0)->pluck('WeeklyTotal')->count();
    }

    /**
     * @return VoteUser
     */
    public static function getWeeklyTopVoter(): VoteUser
    {
        return self::orderByDesc('WeeklyTotal')->whereNull('TopVoterIgnore')->first();
    }

    public static function rollWeeklyWinner(): array
    {
        $votersCount = self::where('WeeklyTotal', '>', 7)->whereNull('TopVoterIgnore')->count();
        if ($votersCount === 0){
            return [];
        }
        $voters = self::where('WeeklyTotal', '>', 7)->whereNull('TopVoterIgnore')->get('PlayerName')->toArray();
        $n = random_int(0, $votersCount);
        shuffle($voters);
        $winner = $voters[$n];
        return [
            'winner' => $winner,
            'number' => $n + 1,
            'totalPlayers' => $votersCount,
        ];
    }
}
