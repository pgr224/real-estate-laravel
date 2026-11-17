<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('builder_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('builder_name');
            $table->string('rera_number')->nullable()->index();
            $table->string('location');
            $table->string('city')->index();
            $table->unsignedInteger('total_units')->default(0);
            $table->unsignedInteger('available_units')->default(0);
            $table->decimal('price_range_min', 15, 2)->nullable();
            $table->decimal('price_range_max', 15, 2)->nullable();
            $table->date('possession_date')->nullable();
            $table->string('status')->default('active')->index();
            $table->json('amenities')->nullable();
            $table->json('floor_plans')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('builder_projects');
    }
};
