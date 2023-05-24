<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservationModel extends Model
{
    use HasFactory;

    protected $guard = 'reservationModel';

    protected $table = 'reservationTable';
    
    protected $guard_name = 'web';

    protected $primaryKey  = 'reservation_id';

    protected $fillable = [
        'user_id',
        'room_id',
        'start_dataTime',
        'end_dateTime',
        'status',
    ];
    protected $hidden = [
        'token',
    ];
}
