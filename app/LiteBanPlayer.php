<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LiteBanPlayer extends Model
{
    protected $table = 'litebans_history';
    protected $connection = 'mysql_litebans';


}
