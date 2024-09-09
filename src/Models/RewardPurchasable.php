<?php

namespace Hyrograsper\LunarRewards\Models;

use Hyrograsper\LunarRewards\Models\Contracts\RewardPurchasable as RewardPurchasableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Lunar\Base\BaseModel;

class RewardPurchasable extends BaseModel implements RewardPurchasableContract
{
    protected $casts = [];

    protected $fillable = [
        'purchasable_type',
        'purchasable_id',
        'type',
    ];

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::modelClass());
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeCondition(Builder $query): Builder
    {
        $query->whereType('condition');
    }
}
