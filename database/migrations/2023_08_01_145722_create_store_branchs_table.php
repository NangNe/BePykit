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
        Schema::create('store_branchs', function (Blueprint $table) {
            $table->id();
            $table->string('id_store_branch');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('place_type_id');
            $table->unsignedBigInteger('address_id');
            $table->string('image_id')->nullable();
            $table->string('name_store_branch');
            $table->string('description')->nullable();
            $table->string('phone_number')->nullable();
            $table->set('status', ['Active', 'Inactive'])->default('Active');
            $table->string('open_time');
            $table->string('close_time');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('place_type_id')->references('id')->on('place_types')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_branchs');
    }
};
