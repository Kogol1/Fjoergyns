<?php

namespace App\Http\Controllers;

use App\VoteUser;
use Carbon\Carbon;

class TopVotersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $top int
     * @return array
     */
    public function getTopVoters($top): array
    {
        $topVoters = VoteUser::orderByDesc('MonthTotal')->take($top)->get();
        return [
            $topVoters->first(),
            $topVoters->get(2),
            $topVoters->get(3),
        ];
    }

    public function postToDiscord(): void
    {
        if (\Carbon\Carbon::now()->endOfMonth()->toDateString() === \Carbon\Carbon::now()->toDateString()){
            $topVoters = $this->getTopVoters(5);
            $hookObject = json_encode([
                "content" => "",
                "username" => "Czech-Survival",
                "avatar_url" => "https://czech-survival.cz/images/index/logo.png",
                "tts" => false,
                "embeds" => [
                    [
                        "title" => "Top vote za měsíc: " . VoteUser::$months[Carbon::yesterday()->month],
                        "type" => "rich",
                        "description" => "Děkujeme všem za vaše hlasy pro náš server. Moc si toho vážíme <:mchearth:520086730515152896>\n Tady jsou borci, kteří hlasovali minulý měsíc nejvíce:",
                        "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),

                        "color" => hexdec("20d4a4"),
                        "author" => [
                            'name' => 'Kogol Bot',
                            'icon_url' => 'https://minotar.net/cube/Kogol/100.png',
                        ],
                        "fields" => [
                            [
                                "name" => ':first_place: 1. místo: '. $topVoters[0]->PlayerName. ' s počtem měsíčních hlasů: '. $topVoters[0]->MonthTotal,
                                "value" => 'Počet hlasů celkem: '.$topVoters[0]->AllTimeTotal,
                                "inline" => false
                            ],
                            [
                                "name" => ':second_place: 2. místo: '. $topVoters[1]->PlayerName. ' s počtem měsíčních hlasů: '. $topVoters[1]->MonthTotal,
                                "value" => 'Počet hlasů celkem: '.$topVoters[2]->AllTimeTotal,
                                "inline" => false
                            ],
                            [
                                "name" => ':third_place: 3. místo: '. $topVoters[2]->PlayerName. ' s počtem měsíčních hlasů: '. $topVoters[2]->MonthTotal,
                                "value" => 'Počet hlasů celkem: '.$topVoters[2]->AllTimeTotal,
                                "inline" => false
                            ],
                            [
                                "name" => 'Ještě jednou díky všem co hlasovali <:CZS:574514963381616651> <:mchearth:520086730515152896>',
                                "value" => '[ @here ]',
                                "inline" => false
                            ]
                        ],
                    ],
                ],

            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => env('DISCORD_WEBHOOK_ANNOUCEMNETS'),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $hookObject,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ]
            ]);

            $response = curl_exec($ch);
            curl_close($ch);
        }
    }
}
