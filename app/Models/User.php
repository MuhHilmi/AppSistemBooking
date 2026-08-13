<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'role', 'password', 'venue_id', 'is_active', 'must_change_password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isPenjaga()
    {
        return $this->role === 'penjaga';
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'owner_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function accessibleVenueIds(): array
    {
        if ($this->isOwner()) {
            return $this->venues()->pluck('id')->all();
        }

        if ($this->isPenjaga()) {
            return $this->venue_id ? [$this->venue_id] : [];
        }

        return [];
    }

    public function canManageVenue(int $venueId): bool
    {
        return in_array($venueId, $this->accessibleVenueIds(), true);
    }
}
