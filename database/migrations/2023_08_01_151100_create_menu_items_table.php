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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('id_menu_item');
            $table->unsignedBigInteger('menu_type_id');
            $table->string('image_id')->nullable();
            $table->string('name_menu_item');
            $table->string('description')->nullable();
            $table->string('price');
            $table->set('status', ['Active', 'Inactive'])->default('Active');
            $table->string('currency') ->nullable();
            $table->string('unit') ->nullable();
            $table->foreign('menu_type_id')->references('id')->on('menu_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
