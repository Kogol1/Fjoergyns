<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoreProtectCommandEco extends CoreProtectCommand
{
    protected $table = 'co_command';
    protected $connection = 'mysql_coreprotect_eco';


}
