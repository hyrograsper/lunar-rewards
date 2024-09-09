<?php

namespace Hyrograsper\LunarRewards\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface RewardCollection
{
    /**
     * Return the reward relationship.
     */
    public function reward(): BelongsTo;

    /**
     * Return the collection relationship.
     */
    public function collection(): BelongsTo;
}
