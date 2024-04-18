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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('id_stores');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('place_type_id');
            $table->string('image_id')->nullable();
            $table->string('name_store');
            $table->string('description')->nullable();
            $table->string('phone_number')->nullable();
            $table->set('status', ['Active', 'Inactive'])->default('Active');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('place_type_id')->references('id')->on('place_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
