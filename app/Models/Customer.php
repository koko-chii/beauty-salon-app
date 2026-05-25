<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
     protected $fillable = [
        'user_id',
        'name',
        'kana',
        'phone',
        'gender',
    ];

    public function visitHistories()
    {
        return $this->hasMany(VisitHistory::class);
    }
}
