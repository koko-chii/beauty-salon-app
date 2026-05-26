<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // 💡 予約データを安全に一括保存するための許可リスト
    protected $fillable = [
        'user_id',
        'customer_id',
        'start_at',
        'end_at',
        'menu',
        'memo',
    ];

    /**
     * 👥 どの顧客の予約か（顧客モデルとのリレーション）
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * 👤 どの美容師（ユーザー）の予約か（ユーザーモデルとのリレーション）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
