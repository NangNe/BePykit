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
        Schema::create('token_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('id_token_notification');
            $table->unsignedBigInteger('user_id');
            $table->string('token_notification');
            $table->string('platform');
            $table->string('type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_notifications');
    }
};