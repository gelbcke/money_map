<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'model',
        'description',
        'readed',
        'need_approval',
        'approved'
    ];

    public function created_at_difference()
    {
        return Carbon::createFromTimestamp(strtotime($this->created_at))->diff(Carbon::now())->days;
    }
}
