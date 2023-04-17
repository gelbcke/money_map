<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'name',
        'wallet_id',
        'f_deb',
        'f_cred',
        'f_invest'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function credit_parcels()
    {
        return $this->hasMany(CreditParcels::class, 'bank_id');
    }

    public function credit_card()
    {
        return $this->hasMany(CreditCard::class, 'bank_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'bank_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'bank_id');
    }
}
