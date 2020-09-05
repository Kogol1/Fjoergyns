<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoreProtectCommand extends Model
{

    protected $table = 'CoreProtectcommand';
    protected $connection = 'mysql_coreprotect';
    protected $primaryKey = 'user';

    protected $fillable = [
        'time', 'user', 'uuid', 'message'
    ];

    public static $uselessCommands = [
        'inventorymirror',
        'kittycannon',
        'ping',
        'ac',
    ];

    public static $privateMessagesCommands = [
        'msg',
        'r',
        'w',
        'whisper',
        'tell',
        'pm',
        'message',
        'privatemessage',
        'mail',
        'reply',
    ];

    public static $teleportationCommands = [
        'tpa',
        'tpahere',
        'home',
        'back',
        'tpaccept',
        'tp',
        'tphere',
        'warp',
    ];

    public function caster()
    {
        return $this->belongsTo(CoreProtectUser::class);
    }

    public function user()
    {
        return CoreProtectUser::find($this->user);

    }

    public function getRoot(): string
    {
        return explode(' ', explode('/', $this->message)[1])[0];
    }

    public function getResTp(): bool
    {
        if ($this->getRoot() === 'res' && isset(explode(' ', explode('/', $this->message)[1])[1]) && explode(' ', explode('/', $this->message)[1])[1] === 'tp') {
            return true;
        }
        return false;
    }

    public function getColor(): string
    {
        if ($this->getResTp()){
            return 'blue';
        }
        if ($this->getRoot() === 'res') {
            return 'green';
        }
        if ($this->msgCoammand()) {
            return 'orange';
        }
        if ($this->teleportationCoammand()) {
            return 'blue';
        }
        if ($this->getRoot() === 'sudo') {
            return 'red';
        }

        return 'black';
    }

    public function uselessCommand(): bool
    {
        if (in_array($this->getRoot(), self::$uselessCommands))
        {
            return true;
        }
        return false;
    }

    public function teleportationCoammand(): bool
    {
        if (in_array($this->getRoot(), self::$teleportationCommands) || $this->getResTp())
        {
            return true;
        }
        return false;
    }

    public function msgCoammand(): bool
    {
        if (in_array($this->getRoot(), self::$privateMessagesCommands))
        {
            return true;
        }
        return false;
    }

}
