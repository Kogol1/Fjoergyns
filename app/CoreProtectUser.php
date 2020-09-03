<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoreProtectUser extends Model
{

    protected $table = 'CoreProtectuser';
    protected $connection = 'mysql_coreprotect';
    protected $primaryKey = 'rowid';

    protected $fillable = [
        'time', 'user', 'uuid',
    ];

    public function commands()
    {
        return $this->belongsToMany(User::class, 'CoreProtectcommand');
    }

}
