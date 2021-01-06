<?php

namespace App\Console\Commands;

use App\CoreProtectBlock;
use App\CoreProtectBlockEco;
use App\VoteUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurgeOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:purge-database';

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
        $start = Carbon::now();
        $time = Carbon::now()->subDays(20)->timestamp;
        CoreProtectBlockEco::where('time', '<', $time)->delete();
        CoreProtectBlock::where('time', '<', $time)->delete();

        $fields = [
            [
                "name" => ':chart_with_upwards_trend: Statistiky:',
                "value" => "Bloky z CoreProtectu byly vymazány. Mazání trvalo: " . Carbon::now()->diffInSeconds($start) . ' sekund',
                "inline" => false,
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
                    "title" => 'Mazání CoreProtect Bloků',
                    "type" => "rich",
                    "description" => '',
                    "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),
                    "footer" => [
                        "text" => "Kogol Bot",
                        "icon_url" => "https://minotar.net/cube/Kogol/100.png"
                    ],
                    "color" => hexdec(sprintf('%06X', mt_rand(0, 0xFFFFFF))),
                    "author" => [
                        'name' => 'CZS System',
                        'icon_url' => 'https://czech-survival.cz/images/index/logo.png',
                    ],
                    "fields" => $fields,
                ],
            ],

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init();

        $webhook = env('DISCORD_WEBHOOK_SERVER_STATUS');
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
