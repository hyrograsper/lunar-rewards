<?php

namespace Hyrograsper\LunarRewards\Models;

use Hyrograsper\LunarRewards\Models\Contracts\RewardCollection as RewardCollectionContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\BaseModel;
use Lunar\Models\Collection;

class RewardCollection extends BaseModel implements RewardCollectionContract
{
    protected $casts = [];

    protected $guarded = [];

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::modelClass());
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::modelClass());
    }
}
