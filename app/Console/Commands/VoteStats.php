<?php

namespace App\Console\Commands;

use App\Vote;
use App\VoteUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class VoteStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:vote-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topVoter24h = Vote::getTopVoter(1);
        $topVoter7d = Vote::getTopVoter(7);
        $topVoter30d = Vote::getTopVoter(30);

        $topVotersText = '';
        $count = 1;
        foreach (Vote::getTopVoters(30, 5) as $topVoter) {
            $topVotersText .= '**' . $count . '/** ' . $topVoter->name . ' - hlasů za měsíc: ' . $topVoter->getSumVotes(30) . "\n";
            $count++;

        }

        $hookObject = json_encode([
            "content" => "",
            "username" => "Czech-Survival",
            "avatar_url" => "https://czech-survival.cz/images/index/logo.png",
            "tts" => false,
            "embeds" => [
                [
                    "title" => Carbon::today()->format('d.m.Y'),
                    "type" => "rich",
                    "description" => "",
                    "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),
                    "footer" => [
                        "text" => "Kogol Bot",
                        "icon_url" => "https://minotar.net/cube/Kogol/100.png"
                    ],
                    "color" => hexdec("20d4a4"),
                    "author" => [
                        'name' => 'CZS Statistiky - hlasování',
                        'icon_url' => 'https://czech-survival.cz/images/index/logo.png',
                    ],
                    "fields" => [
                        [
                            "name" => ':clock1: Statistiky 24 hodin:',
                            "value" => 'Počet hlasů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->count() . "**\n"
                                . 'Top hráč: **' . $topVoter24h->name . '** s počtem hlasů: **' . $topVoter24h->getSumVotes(1) . "**\n"
                                . 'Celkem hráčů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->distinct('player_id')->count() . "**\n"
                            ,
                            "inline" => false,
                        ],
                        [
                            "name" => ':calendar: Statistiky 7 dní:',
                            "value" => 'Počet hlasů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->count() . "**\n"
                                . 'Top hráč: **' . $topVoter7d->name . '** s počtem hlasů: **' . $topVoter7d->getSumVotes(7) . "**\n"
                                . 'Celkem hráčů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->distinct('player_id')->count() . "**\n"
                            ,
                            "inline" => false,
                        ],
                        [
                            "name" => '<:animated_clock:562493945058164739> Statistiky 30 dní:',
                            "value" => 'Počet hlasů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])->count() . "**\n"
                                . 'Top hráči: **' . $topVoter30d->name . '** s počtem hlasů: **' . $topVoter30d->getSumVotes(30) . "**\n"
                                . 'Celkem hráčů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])->distinct('player_id')->count() . "**\n"
                                . $topVotersText
                            ,
                            "inline" => false,
                        ],
                    ],
                ],
            ],

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init();
        $webhook = env('DISCORD_WEBHOOK_LOCAL');
        if (env('APP_ENV') === 'local') {
            $webhook = env('DISCORD_WEBHOOK_LOCAL');
        }
        curl_setopt_array($ch, [
            CURLOPT_URL => $webhook,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ]);

        curl_exec($ch);
        curl_close($ch);
    }
}
