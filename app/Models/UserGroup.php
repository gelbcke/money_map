<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'owner_id',
        'user_id'
    ];

    protected $casts = [
        'user_id' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'group_id');
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class);
    }
}
