<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'due_date',
        'close_invoice',
        'credit_limit'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
