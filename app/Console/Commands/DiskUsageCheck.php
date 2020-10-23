<?php

namespace App\Console\Commands;

use App\Admin;
use App\CoreProtectBlock;
use App\Role;
use App\VoteUser;
use App\VoteUserEco;
use Carbon\Carbon;
use FilesystemIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DiskUsageCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:disk-usage-check';

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
        $diskFreeSpace = round(disk_free_space("/") / 1000000000, 2);

        if ($diskFreeSpace < 30) {
            $fields = [];
            $fields[] = [
                "name" => ':exclamation: Málo místa na disku :exclamation:',
                "value" => 'Na disku zbývá: **' . $diskFreeSpace . 'GB** volného místa' .
                    "\n<@&574196518819463188> <@&679802577080287239>",
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

}
