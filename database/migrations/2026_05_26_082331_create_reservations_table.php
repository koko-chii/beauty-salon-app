<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // 💡 どの美容師（ユーザー）の予約か
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // 💡 どのお客様の予約か（空欄OK。新規の方用に顧客を選ばなくても予約できるように nullable にします）
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            
            // 💡 予約の開始日時と終了日時
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            
            // 💡 メニュー内容やメモ
            $table->string('menu');
            $table->text('memo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
