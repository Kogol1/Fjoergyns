<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordMessage extends Model
{
    const FOOTER_DISABLED = 0;
    const FOOTER_DEFAULT = 1;
    const FOOTER_OWN = 2;


    public function post()
    {
        if ($this->useEmbed) {
            if ($this->footer === 2) {
                $footer = [
                    "footer" => [
                        "text" => $this->footer_text,
                        "icon_url" => $this->footer_img,
                    ],
                ];
            }
            if ($this->footer === 1) {
                $footer = [
                    "footer" => [
                        "text" => config('discord.default_footer_text'),
                        "icon_url" => config('discord.default_footer_img'),
                    ],
                ];
            }

            $embedsData = [
                "embeds" => [
                    [
                        "title" => $this->title ?? 'title',
                        "type" => "rich",
                        "description" => $this->description,
                        "timestamp" => date_format(date_create(), 'Y-m-d\TH:i:sO'),
                        //$footer ?? null,
                        "color" => hexdec("20d4a4"),
                        "author" => [
                            'name' => 'CZS Top Vote',
                            'icon_url' => 'https://czech-survival.cz/images/index/logo.png',
                        ],
                        //"fields" => $this->fields ?? null,
                    ],
                ],
            ];
        }
        $test = a($embedsData['embeds'], true);


        $embeds = "'embeds' => " . $test[0];
        dd($test[10]);

        $content = $this->content ?? config('discord.default_content');
        $hookObject = json_encode([
            "content" => $content,
            "username" => $this->username ?? config('discord.default_username'),
            "avatar_url" => $this->avatar ?? config('discord.default_avatar'),
            "tts" => false,
            $embeds,

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init();
        $webhook = $this->webhook;
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
        /*

        $hookObject = json_encode([
            "content" => "lll",
            "username" => "Czech-Survival",
            "avatar_url" => "https://czech-survival.cz/images/index/logo.png",
            "tts" => false,


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
        */
    }


}
