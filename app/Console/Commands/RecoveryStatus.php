<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecoveryStatus extends Command
{
    protected $signature = 'system:check-recovery {serverName}';

    public function handle()
    {
        $serverName = $this->argument('serverName');
        $fileName = $serverName . '-' . date('Y-m-d') . '.tar.gz';

        $pathToFile = '/home/recovery/' . $serverName . '/' . $fileName;
        if (!file_exists($pathToFile)) {
            $fields[] = [
                "name" => $serverName,
                "value" => 'Záloha souboru: **' . $fileName . '** se nevytvořila ' . $serverName . '. <@&574196518819463188> <@&679802577080287239> :exclamation:',
                "inline" => false
            ];
        } else {
            $fields[] = [
                "name" => $serverName,
                "value" => 'Záloha souboru: **' . $fileName . '** se vytvořila na serveru ' . $serverName . '. Soubor má velikost: ' . round(filesize($pathToFile) / 1000000, 2) . 'MB. <:pepejam:683642409472491520>',
                "inline" => false
            ];
        }

        $diskFreeSpace = round(disk_free_space("/") / 1000000000, 2);

        $fields[] = [
            "name" => 'Celkové místo na disku:',
            "value" => 'Na disku zbývá **' . $diskFreeSpace . ' GB** volného místa',
            "inline" => false
        ];

        $originMachine = [
            "name" => 'Command initiator',
            "value" => 'Machine: ' . env('APP_LOCATION'),
            "inline" => false
        ];
        $fields[] = $originMachine;

        $hookObject = json_encode([
            "content" => "",
            "username" => "Czech-Survival",
            "avatar_url" => "https://czech-survival.cz/images/index/logo.png",
            "tts" => false,
            "embeds" => [
                [
                    "title" => '',
                    "type" => "rich",
                    "description" => '',
                    "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),
                    "footer" => [
                        "text" => 'APP location: ' . env('APP_LOCATION'),
                        "icon_url" => 'https://minotar.net/cube/Kogol/100.png',
                    ],
                    "color" => hexdec("FF6347"),
                    "author" => [
                        'name' => 'Disk Usage Status',
                        'icon_url' => "https://czech-survival.cz/images/index/logo.png",
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
