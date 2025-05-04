<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'profile_photo',
        'notes',
        'is_anonymous',
        'total_donations',
        'donation_count',
        'last_donation_at'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'total_donations' => 'decimal:2',
        'donation_count' => 'integer',
        'last_donation_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get all donations for this donor
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the donor's name for display
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Anonymous' : $this->name;
    }

    /**
     * Get the donor's profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : null;
    }

    /**
     * Get the donor's initials
     */
    public function getInitialsAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'A';
        }
        
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return $initials;
    }

    /**
     * Update donor statistics
     */
    public function updateStatistics(): void
    {
        $this->total_donations = $this->donations()->sum('amount');
        $this->donation_count = $this->donations()->count();
        $this->last_donation_at = $this->donations()->latest()->first()?->created_at;
        $this->save();
    }
}
