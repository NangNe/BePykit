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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('id_promotion');
            $table->unsignedBigInteger('stores_branch_id');
            $table->string('id_image');
            $table->string('name_promotion');
            $table->string('description_promotion');
            $table->string('discount_promotion');
            $table->string('start_date_promotion');
            $table->string('end_date_promotion');
            $table->set('status', ['Active','Inactive','Completed','Cancelled'])->default('Inactive');
            $table->foreign('stores_branch_id')->references('id')->on('store_branchs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
