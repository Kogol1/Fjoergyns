<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoreProtectUserEco extends CoreProtectUser
{
    protected $table = 'co_user';
    protected $connection = 'mysql_coreprotect_eco';
}
