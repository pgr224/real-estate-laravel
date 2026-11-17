<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('owner_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->enum('listing_type', ['sell', 'rent']);
            $table->decimal('asking_price', 15, 2);
            $table->boolean('is_zero_brokerage')->default(false);
            $table->string('contact_phone')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->string('verification_status')->default('pending')->index();
            $table->json('documents')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'listing_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_listings');
    }
};
