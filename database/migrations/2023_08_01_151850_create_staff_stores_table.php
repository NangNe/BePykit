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
        Schema::create('staff_stores', function (Blueprint $table) {
            $table->id();
            $table->string('id_staff_store');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('store_branch_id');
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->set('roleID', ['0','1', '2', '3'])->default('2');
            $table->set('employment_type', ['Parttime', 'Fulltime'])->default('Fulltime');
            $table->string('image_id')->nullable();
            $table->string('wage');
            $table->string('description')->nullable();
            $table->set('status', ['Active', 'Inactive'])->default('Active');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('store_branch_id')->references('id')->on('store_branchs')->onDelete('cascade');
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_stores');
    }
};
