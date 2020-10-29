<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenUser extends Model
{
    protected $table = 'tokenmanager';
    protected $connection = 'mysql_token';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'tokens',
        ];
}
