<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'group_id',
        'user_id',
        'org_bank_id',
        'dest_bank_id',
        'value',
        'date'
    ];

    public function bank_org()
    {
        return $this->belongsTo(Bank::class, 'org_bank_id');
    }

    public function bank_dest()
    {
        return $this->belongsTo(Bank::class, 'dest_bank_id');
    }
}
