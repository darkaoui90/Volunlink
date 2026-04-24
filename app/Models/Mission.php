<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mission extends Model
{
    protected $fillable = [
        'title',
        'site_id',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'required_volunteers',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function volunteers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status', 'late_minutes')
            ->withTimestamps();
    }

    public function getSiteNameAttribute(): string
    {
        return $this->site?->name ?? $this->location;
    }

    public function getSiteCityAttribute(): ?string
    {
        return $this->site?->city;
    }

    public function getDisplayLocationAttribute(): string
    {
        $parts = array_filter([
            $this->site?->name,
            $this->site?->city,
        ]);

        return $parts !== []
            ? implode(', ', $parts)
            : $this->location;
    }

    public function getStatusLabelAttribute(): string
    {
        $today = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        if ($this->date < $today) {
            return 'Completed';
        }

        if ($this->date > $today) {
            return 'Upcoming';
        }

        if ($this->start_time !== null && $this->end_time !== null) {
            if ($this->start_time <= $currentTime && $this->end_time >= $currentTime) {
                return 'Ongoing';
            }

            if ($this->end_time < $currentTime) {
                return 'Completed';
            }
        }

        return 'Upcoming';
    }
}
