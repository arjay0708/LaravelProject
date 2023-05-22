<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class user extends Authenticatable
{
    use HasFactory;

    protected $guard = 'userModel';

    protected $table = 'user';
    
    protected $guard_name = 'web';

    protected $primaryKey  = 'user_id';

    protected $fillable = [
        'photos',
        'lastname',
        'firstname',
        'middlename',
        'extention',
        'phoneNumber',
        'email',
        'password',
        'is_active',
        'is_admin'
    ];
    protected $hidden = [
        'token',
    ];
}
