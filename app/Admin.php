<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['name', 'role', 'active',];

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

    /**
     * @var array
     */
    public static $admins = [
        'Teleriann',
        'Kogol',
        'Flaury',
        'DraganCZ',
        'JustAmynka',
        'JackiiePumpkin',
        '_TotoWolff_',
        'Ansara',
        'Just_Weboo',
        'Console',
        'Pavloskeera',
        'sindy580',
        'Kiklily',
        'NvmYes',
    ];
}
