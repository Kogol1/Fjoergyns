<?php

namespace App\Console\Commands;

use App\Admin;
use App\CoreProtectBlock;
use App\Role;
use App\Server;
use App\VoteUser;
use App\VoteUserEco;
use Carbon\Carbon;
use FilesystemIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DiskUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:disk-usage';

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
        $fields = [];

        //Free space
        $diskFreeSpace = round(disk_free_space("/") / 1000000000, 2);

        $fields[] = [
            "name" => ':floppy_disk: Celkem volného místa:',
            "value" => 'Na disku zbývá: ' . $diskFreeSpace . 'GB volného místa <:pepejam:683642409472491520>',
            "inline" => false
        ];

        $servers = Server::where('check_disk_usage', true)->get();

        foreach ($servers as $server) {
            $path = env('PATH_TO_MINECRAFT_FOLDER').$server->name;
            $bytestotal = 0;
            $path = realpath($path);
            if ($path !== false && $path != '' && file_exists($path)) {
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
                    $bytestotal += $object->getSize();
                }
            }
            $server->disk_size = round($bytestotal / 1000000000, 2);
        }

        $text = '';
        foreach ($servers as $server) {
            $text .= "\n".$server->ds_icon . ' ' . $server->name . ': ' . $server->disk_size . ' GB';
        }

        $fields[] =
            [
                "name" => 'Servery:',
                "value" => $text,
                "inline" => false
            ];

        //Recovery
        $path = env('PATH_TO_RECOVERY_FOLDER');
        $bytestotal = 0;
        $path = realpath($path);
        if ($path !== false && $path != '' && file_exists($path)) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
                $bytestotal += $object->getSize();
            }
        }
        $recoveryFolderSize = round($bytestotal / 1000000000, 2);
        $fields[] =
            [
                "name" => 'Zálohy',
                "value" => ':floppy_disk: Velikost záloh na disku: ' .$recoveryFolderSize. ' GB',
                "inline" => false
            ];

        //Database
        $path = env('PATH_TO_MYSQL_FOLDER');
        $bytestotal = 0;
        $path = realpath($path);
        if ($path !== false && $path != '' && file_exists($path)) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
                $bytestotal += $object->getSize();
            }
        }
        $mysqlFolderSize = round($bytestotal / 1000000000, 2);
        $fields[] =
            [
                "name" => 'Databáze',
                "value" => '<:mysql:764463970916499477> Velikost všech databází: ' .$mysqlFolderSize. " GB",
                "inline" => false
            ];

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
                        "text" => 'APP location: '.env('APP_LOCATION'),
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
