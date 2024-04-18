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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('image_id');
            $table->string('image_query_id');
            $table->string('image_type');
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_name');
            $table->string('image_extension');
            $table->string('image_width');
            $table->string('image_height');
            $table->string('image_mime_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
