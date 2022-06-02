<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'group_id',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function bank()
    {
        return $this->hasMany(Bank::class);
    }

    public function credit()
    {
        return $this->hasMany(CreditCard::class);
    }
}
