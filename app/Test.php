<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $table = 'test';
    protected $dates = 'created_at';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = ['data'];

}
