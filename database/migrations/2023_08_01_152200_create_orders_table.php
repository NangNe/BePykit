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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('id_order');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('staff_id');
            $table->string('name_user')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('description')->nullable();
            $table->set('order_type', ['Reserve', 'Present','NoTable'])->default('Present');
            $table->string('total_price')->nullable();
            $table->set('status', ['Pending', 'Confirmed', 'Completed', 'Cancelled'])->default('Pending');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff_stores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
