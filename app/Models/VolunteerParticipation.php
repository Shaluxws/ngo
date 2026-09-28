<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerParticipation extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_profile_id',
        'activity_name',
        'activity_date',
        'role',
        'hours',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'hours' => 'decimal:2',
    ];

    public function volunteerProfile(): BelongsTo
    {
        return $this->belongsTo(VolunteerProfile::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
