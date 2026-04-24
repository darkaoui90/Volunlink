<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_COORDINATOR = 'coordinator';
    public const ROLE_SUPERVISOR = 'supervisor';
    public const ROLE_VOLUNTEER = 'volunteer';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_COORDINATOR,
        self::ROLE_SUPERVISOR,
        self::ROLE_VOLUNTEER,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'city',
        'languages',
        'skills',
        'availability',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function normalizedRole(): string
    {
        return in_array($this->role, self::ROLES, true)
            ? $this->role
            : self::ROLE_VOLUNTEER;
    }

    public function dashboardRouteName(): string
    {
        return match ($this->normalizedRole()) {
            self::ROLE_ADMIN => 'admin.dashboard',
            self::ROLE_COORDINATOR => 'coordinator.dashboard',
            self::ROLE_SUPERVISOR => 'supervisor.dashboard',
            default => 'volunteer.dashboard',
        };
    }

    public function missions(): BelongsToMany
    {
        return $this->belongsToMany(Mission::class)
            ->withPivot('status', 'late_minutes')
            ->withTimestamps();
    }
}
