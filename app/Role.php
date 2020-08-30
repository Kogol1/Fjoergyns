<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    protected $fillable = ['role_name', 'order',];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }
}
