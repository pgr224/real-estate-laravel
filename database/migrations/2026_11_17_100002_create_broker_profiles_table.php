<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('rera_id')->nullable()->index();
            $table->string('agency_name')->nullable();
            $table->string('specialization')->nullable();
            $table->json('areas_served')->nullable();
            $table->unsignedInteger('experience_years')->default(0);
            $table->unsignedInteger('total_deals')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->boolean('verified')->default(false)->index();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->json('social_links')->nullable();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broker_profiles');
    }
};
