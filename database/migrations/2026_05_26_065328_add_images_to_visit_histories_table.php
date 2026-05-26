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
        Schema::table('visit_histories', function (Blueprint $table) {
            // 画像パスを最大3つ保存できるように追加（空でもいいように nullable にする）
            $table->string('image_path_1')->nullable()->after('memo');
            $table->string('image_path_2')->nullable()->after('image_path_1');
            $table->string('image_path_3')->nullable()->after('image_path_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visit_histories', function (Blueprint $table) {
            $table->dropColumn(['image_path_1', 'image_path_2', 'image_path_3']);
        });
    }
};
