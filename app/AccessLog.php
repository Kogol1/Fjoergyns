<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{

    protected $table = 'log';

    protected $fillable = ['page', 'ip'];


}
