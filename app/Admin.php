<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['name', 'role', 'active', 'ip'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aliases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdminAlias::class);
    }

    public static function getIpAdressessToArray(): array
    {
        return self::whereNotNull('ip')->pluck('ip')->toArray();
    }
}
