<?php

namespace App\Console\Commands;

use App\Ban;
use Illuminate\Console\Command;

class TopBans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:top-bans';

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

        $adminBans = Ban::countBansByAdmins();
        $fields = [];

        foreach ($adminBans as $admin => $bansCount) {
            $fields[] =
                [
                    "name" => $admin,
                    "value" => '⚠ Počet banů celkem: **' . $bansCount .'**'.
                        "\n :clock1: Počet banů za posledních 24 hodin: **" . Ban::countBansInPeriod($admin, 24) .'**'.
                        "\n :calendar: Počet banů za posledních 7 dní: **" . Ban::countBansInPeriod($admin, 24 * 7) .'**'.
                        "\n <:grass_bounce:587505418406723584> Počet banů na Survivalu za posledních 14 dní: **" . Ban::countBansInPeriodDifferServers($admin, 24 * 14)['survival'] .'**'.
                        "\n <:mine_coin:442734230175481856> Počet banů na Economy za posledních 14 dní: **" . Ban::countBansInPeriodDifferServers($admin, 24 * 14)['economy'].'**'.
                        "\n :wink: Počet perma banů celkem: **" . Ban::countPermaBans($admin).'**'. ' | procento perma banů: **' . Ban::getPercentageOfPermaBans($admin).' %**',
                    "inline" => false
                ];
        }
        $fields[] = [
            "name" => 'Neveřejné informace',
            "value" => '**Prosím nesdílejte tyto informace s nikým kdo není v AT. Díky za pochopení** :slight_smile: ',
            "inline" => false
        ];
        $hookObject = json_encode([
            "content" => "",
            "username" => "Czech-Survival",
            "avatar_url" => "https://czech-survival.cz/images/index/logo.png",
            "tts" => false,
            "embeds" => [
                [
                    "title" => 'Počet banů celkem: '.Ban::get('id')->count().
                    "\nPřírůstek za 24 hodin: ". Ban::countWarnsInPeriodWithoutAdmin(24),
                    "type" => "rich",
                    "description" => '',
                    "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),
                    "footer" => [
                        "text" => "Kogol Bot",
                        "icon_url" => "https://minotar.net/cube/Kogol/100.png"
                    ],
                    "color" => hexdec("b80e02"),
                    "author" => [
                        'name' => '📈 Bans stats',
                        'icon_url' => 'https://czech-survival.cz/images/index/logo.png',
                    ],
                    "fields" => $fields,
                ],
            ],

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init();
        $webhook = env('DISCORD_WEBHOOK_ADMIN_STATS');
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
