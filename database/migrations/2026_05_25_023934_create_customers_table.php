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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // 💡 ここから追記します
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 担当美容師（ログインユーザー）のID
            $table->string('name');         // 顧客の名前（例：山田花子）
            $table->string('kana');         // 顧客のフリガナ（例：ヤマダハナコ）
            $table->string('phone')->nullable(); // 電話番号（空っぽでもOKにする）
            $table->string('gender')->nullable(); // 性別（空っぽでもOKにする）
            // 💡 ここまで追記します
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
