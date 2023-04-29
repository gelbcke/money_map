<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'org_id',
        'user_id',
        'group_id',
        'budget_id',
        'date',
        'quantity',
        'ticker',
        'invest_group',
        'sector',
        'buy_price',
        'sell_price',
        'value',
        'bank_id',
        'details',
        'operation'
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

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
