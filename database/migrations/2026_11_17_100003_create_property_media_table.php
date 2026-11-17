<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['video', 'reel', 'virtual_tour', 'drone', 'streetview']);
            $table->string('url');
            $table->string('thumbnail_url')->nullable();
            $table->unsignedInteger('duration')->nullable()->comment('Duration in seconds');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['property_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_media');
    }
};
