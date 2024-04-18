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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('id_table');
            $table->unsignedBigInteger('stores_branch_id');
            $table->unsignedBigInteger('floor_id');
            $table->string('qr_code_id');
            $table->string('capacity');
            $table->string('number_table');
            $table->string('image_id')->nullable();
            $table->set('status', ['Available', 'Occupied', 'Reserved', 'Unavailable'])->default('Available');
            $table->foreign('stores_branch_id')->references('id')->on('store_branchs')->onDelete('cascade');
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
