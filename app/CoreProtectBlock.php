<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoreProtectBlock extends Model
{
    protected $table = 'CoreProtectblock';
    protected $connection = 'mysql_coreprotect';
    protected $primaryKey = 'rowid';

}
