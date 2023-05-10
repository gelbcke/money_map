<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditParcels extends Model
{
    use HasFactory;

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'bank_id',
        'expense_id',
        'date',
        'parcel_nb',
        'parcel_vl'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function credit_card()
    {
        return $this->belongsTo(CreditCard::class, 'bank_id');
    }

    public function credit_cards()
    {
        return $this->hasMany(CreditCard::class, 'bank_id');
    }
}
