<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenUser extends Model
{
    protected $table = 'tokenmanager';
    protected $fillable = [
        'name',
        'tokens',
        ];
}
