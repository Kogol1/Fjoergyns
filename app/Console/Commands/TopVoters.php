<?php

namespace App\Console\Commands;

use App\VoteUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TopVoters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:top-vote';

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
        if (\Carbon\Carbon::now()->endOfMonth()->toDateString() === \Carbon\Carbon::now()->toDateString()) {
            $topVoters = VoteUser::getTopVoters(5);
            $hookObject = json_encode([
                "content" => "",
                "username" => "Czech-Survival",
                "avatar_url" => "https://czech-survival.cz/images/index/logo.png",
                "tts" => false,
                "embeds" => [
                    [
                        "title" => "Top vote za měsíc: " . VoteUser::$months[Carbon::today()->month],
                        "type" => "rich",
                        "description" => "Děkujeme všem za vaše hlasy pro náš server. Moc si toho vážíme <:mchearth:520086730515152896>\n Tady jsou borci, kteří hlasovali minulý měsíc nejvíce:",
                        "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),
                        "footer" => [
                            "text" => "Kogol Bot",
                            "icon_url" => "https://minotar.net/cube/Kogol/100.png"
                        ],
                        "color" => hexdec("20d4a4"),
                        "author" => [
                            'name' => 'CZS Top Vote',
                            'icon_url' => 'https://czech-survival.cz/images/index/logo.png',
                        ],
                        "fields" => [
                            [
                                "name" => ':first_place: 1. místo: ' . $topVoters[0]->PlayerName . ' s počtem měsíčních hlasů: ' . $topVoters[0]->MonthTotal,
                                "value" => 'Počet hlasů celkem: ' . $topVoters[0]->AllTimeTotal,
                                "inline" => false,

                            ],
                            [
                                "name" => ':second_place: 2. místo: ' . $topVoters[1]->PlayerName . ' s počtem měsíčních hlasů: ' . $topVoters[1]->MonthTotal,
                                "value" => 'Počet hlasů celkem: ' . $topVoters[2]->AllTimeTotal,
                                "inline" => false
                            ],
                            [
                                "name" => ':third_place: 3. místo: ' . $topVoters[2]->PlayerName . ' s počtem měsíčních hlasů: ' . $topVoters[2]->MonthTotal,
                                "value" => 'Počet hlasů celkem: ' . $topVoters[2]->AllTimeTotal.
                                "\n\n `Teleriann vám odměny rozdá, stačí mu napsat do přímé zprávy na ds.`",
                                "inline" => false
                            ],
                            [
                                "name" => 'Ještě jednou díky všem co hlasovali <:CZS:574514963381616651> <:mchearth:520086730515152896>',
                                "value" => '[ @everyone ]',
                                "inline" => false
                            ]
                        ],
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
}
