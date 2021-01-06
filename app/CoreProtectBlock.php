<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoreProtectBlock extends Model
{
    protected $table = 'block';
    protected $connection = 'mysql_coreprotect';
    protected $primaryKey = 'rowid';

}
