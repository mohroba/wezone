<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisable_types', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('model_class');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('advertisable_types')->insert([
            [
                'key' => 'car',
                'label' => 'Cars',
                'model_class' => \Modules\Ad\Models\AdCar::class,
                'description' => 'Vehicles, trucks, and cars listings.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'real_estate',
                'label' => 'Real Estate',
                'model_class' => \Modules\Ad\Models\AdRealEstate::class,
                'description' => 'Residential and commercial real estate listings.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'job',
                'label' => 'Jobs',
                'model_class' => \Modules\Ad\Models\AdJob::class,
                'description' => 'Job postings and employment opportunities.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisable_types');
    }
};
