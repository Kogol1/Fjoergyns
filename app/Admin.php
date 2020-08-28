<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Admin extends Model
{
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
