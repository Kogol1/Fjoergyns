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


            $value = '';
            $count = 1;

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
                                "name" => ':clock1: Statistiky 24h:',
                                "value" => 'Počet hlasů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->count()."**\n"
                                .'Top hráč: **' . Vote::getTopVoter(1)->name.'** s počtem hlasů: **'. Vote::getTopVoter(1)->getSumVotes(1) . "**\n"
                                .'Celkem hráčů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->distinct('name')->count()."**\n"
                                ,
                                "inline" => false,
                            ],
                            [
                                "name" => ':calendar: Statistiky 7d:',
                                "value" => 'Počet hlasů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->count()."**\n"
                                    .'Top hráč: **' . Vote::getTopVoter(7)->name.'** s počtem hlasů: **'. Vote::getTopVoter(7)->getSumVotes(7) . "**\n"
                                    .'Celkem hráčů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->distinct('name')->count()."**\n"
                                ,
                                "inline" => false,
                            ],
                            [
                                "name" => ':calendar: Statistiky 30d:',
                                "value" => 'Počet hlasů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])->count()."**\n"
                                    .'Top hráč: **' . Vote::getTopVoter(30)->name.'** s počtem hlasů: **'. Vote::getTopVoter(30)->getSumVotes(30) . "**\n"
                                    .'Celkem hráčů: **' . Vote::whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])->distinct('name')->count()."**\n"
                                ,
                                "inline" => false,
                            ],
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
