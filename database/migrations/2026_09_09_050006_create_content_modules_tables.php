<?php

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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('badge')->nullable()->default('FEATURED CAMPAIGN');
            $table->text('subtitle')->nullable();
            $table->decimal('goal_amount', 12, 2)->default(500000);
            $table->decimal('raised_amount', 12, 2)->default(0);
            $table->unsignedInteger('supporters_count')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('image_url')->nullable();
            $table->string('image_alt')->nullable();
            $table->json('impact_bullets')->nullable();
            $table->json('suggested_amounts')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('General');
            $table->date('event_date')->index();
            $table->string('time_info')->nullable();
            $table->string('location');
            $table->text('description');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('image_url')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('leaders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('bio');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('image_url')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author_info');
            $table->text('excerpt');
            $table->text('quote')->nullable();
            $table->string('category')->default('Education');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('image_url')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Community');
            $table->string('location')->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('image_url')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('stories');
        Schema::dropIfExists('leaders');
        Schema::dropIfExists('events');
        Schema::dropIfExists('campaigns');
    }
};
