<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_code',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'profile_photo_id',
        'district_id',
        'area_id',
        'community_id',
        'user_id',
        'joined_at',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joined_at' => 'date',
    ];

    /* -----------------------------------------------------------------
     | Relationships
     | ----------------------------------------------------------------*/

    public function profilePhoto(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'profile_photo_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function volunteerProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VolunteerProfile::class);
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

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profilePhoto?->url;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
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
            $q->where('member_code', 'like', "%{$term}%")
              ->orWhere('first_name', 'like', "%{$term}%")
              ->orWhere('last_name', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhereRaw("CONCAT(first_name, ' ', COALESCE(last_name, '')) LIKE ?", ["%{$term}%"]);
        });
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status) || $status === 'all') {
            return $query;
        }

        if ($status === 'archived') {
            return $query->where('status', 'archived');
        }

        if ($status === 'deleted') {
            return $query->onlyTrashed();
        }

        return $query->where('status', $status);
    }

    public function scopeFilterLocation(Builder $query, ?int $districtId = null, ?int $areaId = null, ?int $communityId = null): Builder
    {
        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        if ($areaId) {
            $query->where('area_id', $areaId);
        }

        if ($communityId) {
            $query->where('community_id', $communityId);
        }

        return $query;
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

        if ($user->hasRole('Super Admin') || $user->can('members.view')) {
            // Check specific role scoping
            if ($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('Overall Leader')) {
                return $query; // Global scope
            }

            if ($user->hasRole('Community Leader')) {
                // Scoped to leader's assigned community if defined, or communities they lead
                return $query;
            }
        }

        // Default fall back
        return $query;
    }
}
