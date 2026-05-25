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
        Schema::create('visit_histories', function (Blueprint $table) {
            $table->id();
            // 💡 ここから追記します
            // 「どのお客様のカルテか」を記録する外部キー（お客様が消えたらカルテも自動連動で消える設定）
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete(); 
            $table->date('visited_at');     // 来店日（例：2026-05-25）
            $table->string('menu');         // メニュー（例：カット＋カラー）
            $table->text('memo')->nullable(); // 髪質やカラー調合のメモ（長い文章OK、空っぽでもOK）
            // 💡 ここまで追記します
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_histories');
    }
};
