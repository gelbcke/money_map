<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $casts = [
        'value' => 'float',
        'parcels' => 'integer',
        'payment_method' => 'integer'
    ];

    protected $dates = [
        'date',
        'end_parcels',
        'due_date'
    ];

    protected $fillable = [
        'group_id',
        'user_id',
        'budget_id',
        'category_id',
        'date',
        'value',
        'bank_id',
        'parcels',
        'parcel_vl',
        'details',
        'rec_expense',
        'payment_method',
        'end_parcels'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
