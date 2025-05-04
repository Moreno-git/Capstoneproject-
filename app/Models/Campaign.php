<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'goal_amount',
        'funds_raised',
        'status',
        'image',
        'start_date',
        'end_date',
        'category_id',
        'pledged_amount',
        'pledged_quantity',
        'color',
        'total_donations',
        'is_urgent',
        'is_archived'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_urgent' => 'boolean',
        'is_archived' => 'boolean',
        'goal_amount' => 'decimal:2',
        'funds_raised' => 'decimal:2',
        'total_donations' => 'decimal:2'
    ];

    /**
     * Get all donations for this campaign
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Calculate the progress percentage of the campaign
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->goal_amount <= 0) {
            return 0;
        }
        return min(($this->total_donations / $this->goal_amount) * 100, 100);
    }

    /**
     * Get the campaign's status based on end date and progress
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_archived) {
            return 'archived';
        }
        if ($this->end_date < now()) {
            return 'ended';
        }
        if ($this->progress_percentage >= 100) {
            return 'completed';
        }
        return 'active';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Update the total donations for this campaign
     */
    public function updateTotalDonations(): void
    {
        $this->total_donations = $this->donations()->sum('amount');
        $this->save();
    }
}
