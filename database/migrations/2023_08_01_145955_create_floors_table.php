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
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->string('id_floors');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('store_branch_id');
            $table->string('name_floors');
            $table->string('floor_number')->nullable();
            $table->string('description')->nullable();
            $table->string('image_id')->nullable();
            $table->set('status', ['Active', 'Inactive'])->default('Active');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('store_branch_id')->references('id')->on('store_branchs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};
