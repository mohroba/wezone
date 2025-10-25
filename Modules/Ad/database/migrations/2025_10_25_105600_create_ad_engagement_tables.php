<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_views', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->foreignId('viewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();

            $table->index(['ad_id', 'viewed_at']);
            $table->index(['ad_id', 'viewer_id']);
        });

        Schema::create('ad_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('ad_comments')->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['ad_id', 'parent_id', 'created_at']);
        });

        Schema::create('ad_likes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ad_id', 'user_id']);
        });

        Schema::create('ad_conversations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['ad_id', 'seller_id', 'buyer_id']);
            $table->index(['seller_id', 'buyer_id']);
        });

        Schema::create('ad_conversation_participants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->constrained('ad_conversations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->unique(['conversation_id', 'user_id']);
            $table->index(['user_id', 'deleted_at']);
        });

        Schema::create('ad_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->constrained('ad_conversations')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['conversation_id', 'created_at']);
        });

        Schema::table('ad_conversations', function (Blueprint $table): void {
            $table->foreign('last_message_id')->references('id')->on('ad_messages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ad_conversations', function (Blueprint $table): void {
            $table->dropForeign(['last_message_id']);
        });

        Schema::dropIfExists('ad_messages');
        Schema::dropIfExists('ad_conversation_participants');
        Schema::dropIfExists('ad_conversations');
        Schema::dropIfExists('ad_likes');
        Schema::dropIfExists('ad_comments');
        Schema::dropIfExists('ad_views');
    }
};
