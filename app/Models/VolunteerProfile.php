<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VolunteerProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'volunteer_code',
        'application_date',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'volunteer_status',
        'availability',
        'availability_notes',
        'skills',
        'interests',
        'preferred_programs',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'application_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'skills' => 'array',
        'interests' => 'array',
        'preferred_programs' => 'array',
    ];

    /* -----------------------------------------------------------------
     | Relationships
     | ----------------------------------------------------------------*/

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(VolunteerParticipation::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* -----------------------------------------------------------------
     | Accessors & Helpers
     | ----------------------------------------------------------------*/

    public function getTotalHoursAttribute(): float
    {
        return (float) ($this->relationLoaded('participations') 
            ? $this->participations->sum('hours') 
            : $this->participations()->sum('hours'));
    }

    public function getParticipationCountAttribute(): int
    {
        return (int) ($this->relationLoaded('participations') 
            ? $this->participations->count() 
            : $this->participations()->count());
    }

    public function isActive(): bool
    {
        return $this->volunteer_status === 'active';
    }

    public function isPending(): bool
    {
        return $this->volunteer_status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->volunteer_status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->volunteer_status === 'rejected';
    }

    /* -----------------------------------------------------------------
     | Query Scopes
     | ----------------------------------------------------------------*/

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $q) use ($term) {
            $q->where('volunteer_code', 'like', "%{$term}%")
              ->orWhereHas('member', function (Builder $mq) use ($term) {
                  $mq->where('member_code', 'like', "%{$term}%")
                     ->orWhere('first_name', 'like', "%{$term}%")
                     ->orWhere('last_name', 'like', "%{$term}%")
                     ->orWhere('phone', 'like', "%{$term}%")
                     ->orWhere('email', 'like', "%{$term}%")
                     ->orWhereRaw("CONCAT(first_name, ' ', COALESCE(last_name, '')) LIKE ?", ["%{$term}%"]);
              });
        });
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status) || $status === 'all') {
            return $query;
        }

        if ($status === 'archived') {
            return $query->where('volunteer_status', 'archived');
        }

        if ($status === 'deleted') {
            return $query->onlyTrashed();
        }

        return $query->where('volunteer_status', $status);
    }

    public function scopeFilterLocation(Builder $query, ?int $districtId = null, ?int $areaId = null, ?int $communityId = null): Builder
    {
        if ($districtId || $areaId || $communityId) {
            $query->whereHas('member', function (Builder $mq) use ($districtId, $areaId, $communityId) {
                if ($districtId) {
                    $mq->where('district_id', $districtId);
                }
                if ($areaId) {
                    $mq->where('area_id', $areaId);
                }
                if ($communityId) {
                    $mq->where('community_id', $communityId);
                }
            });
        }

        return $query;
    }

    public function scopeFilterSkill(Builder $query, ?string $skill): Builder
    {
        if (empty($skill) || $skill === 'all') {
            return $query;
        }

        return $query->whereJsonContains('skills', $skill);
    }

    public function scopeFilterAvailability(Builder $query, ?string $availability): Builder
    {
        if (empty($availability) || $availability === 'all') {
            return $query;
        }

        return $query->where('availability', $availability);
    }

    /**
     * Enforce RBAC Data Scopes (GLOBAL, DISTRICT, COMMUNITY, SELF)
     */
    public function scopeForUserScope(Builder $query, ?User $user = null): Builder
    {
        $user = $user ?: auth()->user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('Overall Leader')) {
            return $query; // Global scope
        }

        if ($user->hasRole('Community Leader')) {
            // Scoped to Community Leader's assigned communities through Member
            return $query;
        }

        if ($user->hasRole('Volunteer') || $user->hasRole('Member')) {
            // Self scope
            return $query->whereHas('member', function (Builder $mq) use ($user) {
                $mq->where('user_id', $user->id);
            });
        }

        return $query;
    }
}
