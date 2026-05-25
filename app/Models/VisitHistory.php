<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitHistory extends Model
{
     protected $fillable = [
        'customer_id',
        'visited_at',
        'menu',
        'memo',
    ];

     public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
