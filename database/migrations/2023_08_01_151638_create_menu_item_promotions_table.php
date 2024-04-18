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
        Schema::create('menu_item_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('id_menu_item_promotion');
            $table->unsignedBigInteger('menu_item_id');
            $table->unsignedBigInteger('promotion_id');
            $table->string('image_id')->nullable();
            $table->string('discounted_price');
            $table->set('status', ['Active', 'Inactive'])->default('Active');
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_promotions');
    }
};
