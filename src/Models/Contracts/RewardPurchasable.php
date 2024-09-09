<?php

namespace Hyrograsper\LunarRewards\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface RewardPurchasable
{
    /**
     * Return the reward relationship.
     */
    public function reward(): BelongsTo;

    /**
     * Return the priceable relationship.
     */
    public function purchasable(): MorphTo;

    /**
     * Scope a query where type is condition.
     */
    public function scopeCondition(Builder $query): Builder;
}
