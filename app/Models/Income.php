<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $casts = [
        'value' => 'float',
        'rec_income' => 'integer'
    ];

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'org_id',
        'group_id',
        'user_id',
        'date',
        'value',
        'bank_id',
        'rec_income',
        'details'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
