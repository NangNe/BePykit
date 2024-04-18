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
        Schema::create('menu_types', function (Blueprint $table) {
            $table->id();
            $table->string('id_menu_type');
            $table->unsignedBigInteger('stores_branch_id');
            $table->string('image_id')->nullable();
            $table->string('name_menu_type');
            $table->string('description')->nullable();
            $table->set('status', ['Active', 'Inactive'])->default('Active');
            $table->foreign('stores_branch_id')->references('id')->on('store_branchs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_types');
    }
};
