<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UsersShift extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'substitute_user_id',
        'temp_changes',
        'date_from',
        'date_to'
    ];
    public $timestamps = false;

    public function scopeCreateShifts($query)
    {
        return $query->where('date_from', Carbon::now()->format('Y-m-d'))->get([
            'id',
            'user_id',
            'substitute_user_id',
            'temp_changes'
        ]);
    }
    public function scopeRestoreShifts($query)
    {
        return $query->where('date_to', Carbon::now()->subDays(1)->format('Y-m-d'))->get([
            'id',
            'user_id',
            'substitute_user_id',
            'temp_changes'
        ]);
    }

}
