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
        Schema::create('stampings', function (Blueprint $table) {
            $table->id(); //ID
            $table->unsignedBigInteger('user_id'); //整数カラム
            $table->date('date'); //出勤日
            $table->timestamp('clock_in')->nullable(); //出勤時間
            $table->timestamp('clock_out')->nullable(); //退勤時間
            $table->timestamps();
            
            //外部キー制約（stampingのuser_idテーブルに外部キー制約を設定）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stampings');
    }
};
