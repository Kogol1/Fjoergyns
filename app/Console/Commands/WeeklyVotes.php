<?php

namespace App\Console\Commands;

use App\Player;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Console\Command;

class WeeklyVotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:weekly-vote';

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
        $lottery = Player::rollWeeklyWinner();
        if (empty($lottery)) {
            $lotteryField = [
                "name" => ':first_place: Výherce loterie',
                "value" => 'Tuto loterii nikdo nevyhrává. Žádný z hráčů nesplnil podmínky soutěže. Pokud se chcete zapojit do loterie na příští týden, přečtěte si pravidla, aby jste splnili podmínky slosování.',
                "inline" => true
            ];
        } else {
            $lotteryField = [
                "name" => ':first_place: Výherce loterie',
                "value" => 'Výhercem se stává: **' . $lottery['winner'] . '** s výherním číslem: **' . $lottery['number'] .
                    "\n **Do loterie bylo zapsáno **" . $lottery['totalPlayers'] . ' hráčů**.' .
                    "\n\n `Výhra: 25 bodů do voteshopu (bude vám připsána automaticky)`",
                "inline" => true
            ];
        }

        $fields = [
            [
                "name" => ':chart_with_upwards_trend: Statistiky:',
                "value" => "Počet hlasů za týden: **" . Vote::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->count() . '**' .
                    "\n Počet hráčů, kteří tento týden alespoň jednou zahlasovali: **" . Vote::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->distinct('player_id')->count() . '**' .
                    "\n Nejvíce za tento týden hlasoval hráč: **" . Vote::getTopVoter(7)->name . '** s počtem hlasů: **' . Vote::getTopVoter(7)->getSumVotes(7) . '**',
                "inline" => false,
            ],
            $lotteryField,
            [
                "name" => ':question: Jak se zapojit do loterie',
                "value" => 'Do loterie se automaticky započítává každý hráč, který má za týden více jak 7 hlasů. Každý takový hráč má stejnou váhu a je jedno jestli hlasoval víc nebo ne.',
                "inline" => true
            ],
            [
                "name" => 'Děkujeme všem hráčům, kteří hlasovali',
                "value" => '<:CZS:574514963381616651> <:mchearth:520086730515152896>',
                "inline" => false
            ],
        ];

        if (env('APP_ENV') === 'localhost') {
            $debugField = [
                "name" => 'DEBUG MODE <:mc_bee:614491304491089930>',
                "value" => 'Initiator: ' . env('APP_LOCATION'),
                "inline" => false
            ];
            $fields[] = $debugField;
        }


        $hookObject = json_encode([
            "content" => "",
            "username" => "Czech-Survival",
            "avatar_url" => "https://czech-survival.cz/images/index/logo.png",
            "tts" => false,
            "embeds" => [
                [
                    "title" => 'Týdenní shrnutí (' . Carbon::today()->subDays(7)->format('d.m.Y') . ' - ' . Carbon::today()->format('d.m.Y') . ')',
                    "type" => "rich",
                    "description" => '',
                    "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),
                    "footer" => [
                        "text" => "Kogol Bot",
                        "icon_url" => "https://minotar.net/cube/Kogol/100.png"
                    ],
                    "color" => hexdec(sprintf('%06X', mt_rand(0, 0xFFFFFF))),
                    "author" => [
                        'name' => 'CZS Top Vote',
                        'icon_url' => 'https://czech-survival.cz/images/index/logo.png',
                    ],
                    "fields" => $fields,
                ],
            ],

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init();

        $webhook = env('DISCORD_WEBHOOK_ANNOUCEMNETS');
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
