<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('rera_number')->nullable()->index()->after('postal_code');
            $table->decimal('carpet_area_sqft', 10, 2)->nullable()->after('rera_number');
            $table->decimal('super_buildup_area_sqft', 10, 2)->nullable()->after('carpet_area_sqft');
            $table->unsignedInteger('floor_number')->nullable()->after('super_buildup_area_sqft');
            $table->unsignedInteger('total_floors')->nullable()->after('floor_number');
            $table->string('facing')->nullable()->after('total_floors');
            $table->enum('furnishing', ['unfurnished', 'semi', 'fully'])->nullable()->after('facing');
            $table->unsignedInteger('parking_count')->default(0)->after('furnishing');
            $table->unsignedInteger('property_age')->nullable()->after('parking_count')->comment('Age in years');
            $table->decimal('maintenance_charge', 10, 2)->nullable()->after('property_age');
            $table->string('city')->nullable()->index()->after('maintenance_charge');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode', 10)->nullable()->index()->after('state');
            $table->foreignId('builder_project_id')->nullable()->after('pincode')->constrained('builder_projects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['builder_project_id']);
            $table->dropColumn([
                'rera_number', 'carpet_area_sqft', 'super_buildup_area_sqft',
                'floor_number', 'total_floors', 'facing', 'furnishing',
                'parking_count', 'property_age', 'maintenance_charge',
                'city', 'state', 'pincode', 'builder_project_id',
            ]);
        });
    }
};
