<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'role'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'password'          => 'hashed',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::created(function (self $user): void {
            $roleName = _getRoleName($user->role);
            $user->syncRoles(Role::findByName($roleName, 'web'));
        });

        static::updated(function (self $user): void {
            if ($user->isDirty('role')) {
                $roleName = _getRoleName($user->role);
                $user->syncRoles(Role::findByName($roleName, 'web'));
            }
        });
    }

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class)
            ->withPivot(['read', 'created_at']);
    }

    public function unreadNotifications()
    {
        return $this->notifications()
            ->wherePivot('read', false);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()
            ->wherePivot('read', false)
            ->count();
    }
}
