<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrgentCampaign extends Model
{
    protected $fillable = [
        'title',
        'description',
        'goal',
        'raised',
        'is_urgent',
    ];

    protected $casts = [
        'goal' => 'decimal:2',
        'raised' => 'decimal:2',
        'is_urgent' => 'boolean',
    ];

    /**
     * Get the progress percentage of the campaign.
     */
    public function getProgressAttribute(): int
    {
        return $this->goal > 0 ? intval(($this->raised / $this->goal) * 100) : 0;
    }
}