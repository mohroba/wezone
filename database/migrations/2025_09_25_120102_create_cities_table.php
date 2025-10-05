<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('province');
            $table->string('name', 255);
            $table->string('name_en', 255);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            $table
                ->foreign('province')
                ->references('id')
                ->on('provinces')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
