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
        Schema::dropIfExists('volunteer_participations');
        Schema::dropIfExists('volunteer_profiles');

        Schema::create('volunteer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->unique()->constrained('members')->cascadeOnDelete();
            $table->string('volunteer_code', 30)->unique();
            $table->date('application_date')->index();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->string('volunteer_status', 20)->default('pending')->index(); // pending, approved, active, inactive, suspended, rejected, archived
            $table->string('availability', 30)->nullable(); // weekdays, weekends, both, flexible
            $table->text('availability_notes')->nullable();
            $table->json('skills')->nullable();
            $table->json('interests')->nullable();
            $table->json('preferred_programs')->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 30)->nullable();
            $table->string('emergency_contact_relation', 50)->nullable();
            $table->text('notes')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for fast scoped filtering
            $table->index(['volunteer_status', 'application_date'], 'vol_status_app_date_idx');
        });

        Schema::create('volunteer_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_profile_id')->constrained('volunteer_profiles')->cascadeOnDelete();
            $table->string('activity_name', 255);
            $table->date('activity_date')->index();
            $table->string('role', 100)->nullable();
            $table->decimal('hours', 6, 2)->default(0);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['volunteer_profile_id', 'activity_date'], 'vol_part_profile_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_participations');
        Schema::dropIfExists('volunteer_profiles');
    }
};
