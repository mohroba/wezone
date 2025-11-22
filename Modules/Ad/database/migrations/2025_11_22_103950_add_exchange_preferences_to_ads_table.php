<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->boolean('comment_enable')->default(true)->after('is_exchangeable');
            $table->boolean('phone_enable')->default(true)->after('comment_enable');
            $table->boolean('chat_enable')->default(true)->after('phone_enable');
            $table->bigInteger('extra_amount')->default(0)->after('chat_enable');
            $table->text('exchange_description')->nullable()->after('extra_amount');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn([
                'comment_enable',
                'phone_enable',
                'chat_enable',
                'extra_amount',
                'exchange_description',
            ]);
        });
    }
};
