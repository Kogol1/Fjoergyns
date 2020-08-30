<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminAlias extends Model
{
    protected $fillable = ['alias_name', 'admin_id', ];
    protected $table = 'admin_alias';

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
