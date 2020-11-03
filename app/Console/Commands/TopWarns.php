<?php

namespace App\Console\Commands;

use App\Warn;
use Illuminate\Console\Command;

class TopWarns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'czs:top-warns';

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

        $adminWarns = Warn::countWarnsByAdmins();
        $fields = [];

        foreach ($adminWarns as $admin => $warnsCount) {
            $fields[] =
                [
                    "name" => $admin,
                    "value" => '⚠ Počet warnů celkem: **' . $warnsCount .'**'.
                        "\n :clock1: Počet warnů za posledních 24 hodin: **" . Warn::countWarnsInPeriod($admin, 24) .'**'.
                        "\n :calendar: Počet warnů za posledních 7 dní: **" . Warn::countWarnsInPeriod($admin, 24 * 7) .'**'.
                        "\n <:grass_bounce:587505418406723584> Počet warnů na Survivalu za posledních 14 dní: **" . Warn::countWarnsInPeriodDifferServers($admin, 24 * 14)['survival'] .'**'.
                        "\n <:mine_coin:442734230175481856> Počet warnů na Economy za posledních 14 dní: **" . Warn::countWarnsInPeriodDifferServers($admin, 24 * 14)['economy'].'**',
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
                    "title" => 'Počet warnů celkem: '.Warn::get('id')->count().
                        "\nPřírůstek za 24 hodin: ". Warn::countWarnsInPeriodWithoutAdmin(24),
                    "type" => "rich",
                    "description" => '',
                    "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),
                    "footer" => [
                        "text" => "Kogol Bot",
                        "icon_url" => "https://minotar.net/cube/Kogol/100.png"
                    ],
                    "color" => hexdec("FF6347"),
                    "author" => [
                        'name' => '📈 Warns stats',
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
