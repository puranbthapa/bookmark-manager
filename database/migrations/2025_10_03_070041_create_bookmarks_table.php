<?php

/**
 * ================================================================================
 * BOOKMARKS TABLE MIGRATION - DATABASE SCHEMA
 * ================================================================================
 *
 * 🏢 VENDOR: Eastlink Cloud Pvt. Ltd.
 * 👨‍💻 AUTHOR: Developer Team
 * 📅 CREATED: October 2025
 * 📧 CONTACT: puran@eastlink.net.np
 * 📞 PHONE: +977-01-4101181
 * 📱 DEVELOPER: +977-9801901140
 * 💼 BUSINESS: +977-9801901141
 * 🏢 ADDRESS: Tripureshwor, Kathmandu, Nepal
 *
 * 📋 DESCRIPTION:
 * Database migration for creating the bookmarks table with comprehensive
 * fields for advanced bookmark management functionality.
 *
 * 🎯 SCHEMA FEATURES:
 * - Multi-user bookmark support
 * - Category and tag relationships
 * - Metadata and thumbnail storage
 * - Visit tracking and analytics
 * - Favorite and privacy controls
 * - Short code generation
 * - Status and archival system
 *
 * ⚖️ LICENSE: Commercial Enterprise License
 * ================================================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('url');
            $table->text('description')->nullable();
            $table->string('favicon')->nullable();
            $table->string('thumbnail')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['active', 'archived', 'broken'])->default('active');
            $table->boolean('favorite')->default(false);
            $table->boolean('private')->default(false);
            $table->integer('visits')->default(0);
            $table->string('short_code', 10)->unique()->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'favorite']);
            $table->index(['user_id', 'category_id']);
            $table->fullText(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
