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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code', 30)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('phone', 30)->nullable()->index();
            $table->string('email', 150)->nullable()->index();

            // Media & Location Relationships
            $table->foreignId('profile_photo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('community_id')->nullable()->constrained('communities')->nullOnDelete();

            // Optional User Account Linkage
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Membership Details
            $table->date('joined_at')->index();
            $table->string('status', 20)->default('active')->index(); // pending, active, inactive, suspended, archived
            $table->text('notes')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for fast searching and multi-filter queries
            $table->index(['status', 'district_id']);
            $table->index(['status', 'community_id']);
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
