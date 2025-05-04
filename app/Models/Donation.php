<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'donor_id',
        'campaign_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'is_anonymous',
        'type',
        'amount',
        'item_description',
        'quantity',
        'status',
        'payment_method',
        'transaction_id',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expected_date' => 'datetime'
    ];

    // Make sure timestamps are enabled
    public $timestamps = true;

    /**
     * Get the campaign that this donation belongs to
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the donor that this donation belongs to
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Get the donor name for display
     */
    public function getDonorDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }
        return $this->donor_name ?? 'Anonymous';
    }

    /**
     * Get the status color for badges
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'success',
            'pending' => 'warning',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Scope a query to only include completed donations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include monetary donations
     */
    public function scopeMonetary($query)
    {
        return $query->where('type', 'monetary');
    }

    /**
     * Scope a query to only include non-monetary donations
     */
    public function scopeNonMonetary($query)
    {
        return $query->where('type', 'non-monetary');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a donation
        static::creating(function ($donation) {
            if (!$donation->created_at) {
                $donation->created_at = now();
            }
            if (!$donation->updated_at) {
                $donation->updated_at = now();
            }

            // Try to find or create donor
            if (!$donation->donor_id && $donation->donor_email) {
                $donor = Donor::firstOrCreate(
                    ['email' => $donation->donor_email],
                    [
                        'name' => $donation->donor_name,
                        'phone' => $donation->donor_phone,
                        'is_anonymous' => $donation->is_anonymous
                    ]
                );
                $donation->donor_id = $donor->id;
            }
        });

        // After donation is created or updated
        static::saved(function ($donation) {
            // Update donor statistics if we have a donor
            if ($donation->donor_id) {
                $donation->donor->updateStatistics();
            }
        });

        // Before donation is deleted
        static::deleting(function ($donation) {
            // Update donor statistics if we have a donor
            if ($donation->donor_id) {
                $donation->donor->updateStatistics();
            }
        });
    }
} 