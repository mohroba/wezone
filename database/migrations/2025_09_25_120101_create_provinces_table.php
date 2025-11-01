<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name', 255);
            $table->string('name_en', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
