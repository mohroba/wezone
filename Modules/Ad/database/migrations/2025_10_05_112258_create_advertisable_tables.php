<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_cars', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->nullable();
            $table->foreignId('model_id')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedInteger('mileage')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('body_style')->nullable();
            $table->string('color')->nullable();
            $table->string('condition')->nullable();
            $table->unsignedTinyInteger('ownership_count')->nullable();
            $table->string('vin', 64)->nullable();
            $table->date('registration_expiry')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->timestamps();

            $table->index(['brand_id', 'model_id']);
        });

        Schema::create('ad_real_estates', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('property_type')->nullable();
            $table->string('usage_type')->nullable();
            $table->unsignedDecimal('area_m2', 10, 2)->nullable();
            $table->unsignedDecimal('land_area_m2', 10, 2)->nullable();
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->unsignedTinyInteger('parking_spaces')->nullable();
            $table->integer('floor_number')->nullable();
            $table->integer('total_floors')->nullable();
            $table->unsignedSmallInteger('year_built')->nullable();
            $table->string('document_type')->nullable();
            $table->boolean('has_elevator')->default(false);
            $table->boolean('has_storage')->default(false);
            $table->json('utilities_json')->nullable();
            $table->timestamps();
        });

        Schema::create('ad_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('company_name');
            $table->string('position_title');
            $table->string('industry')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('education_level')->nullable();
            $table->bigInteger('salary_min')->nullable();
            $table->bigInteger('salary_max')->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('salary_type')->nullable();
            $table->string('work_schedule')->nullable();
            $table->string('remote_level')->nullable();
            $table->json('benefits_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_jobs');
        Schema::dropIfExists('ad_real_estates');
        Schema::dropIfExists('ad_cars');
    }
};
