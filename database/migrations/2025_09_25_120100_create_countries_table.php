<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('capital_city')->nullable();
            $table->string('name', 255);
            $table->string('name_en', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
