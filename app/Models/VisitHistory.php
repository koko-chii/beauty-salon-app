<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitHistory extends Model
{
    use HasFactory;

    // 💡 超重要： image_path_1, 2, 3 の保存を100%許可する設定です
    protected $fillable = [
        'customer_id',
        'visited_at',
        'menu',
        'memo',
        'image_path_1',
        'image_path_2',
        'image_path_3',
    ];

    /**
     * 顧客とのリレーション（必要に応じて）
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
