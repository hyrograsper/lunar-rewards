<?php

namespace Hyrograsper\LunarRewards\Models;

use Illuminate\Support\Collection;

class Cart extends \Lunar\Models\Cart
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->cachableProperties = array_merge(
            $this->cachableProperties,
            [
                'rewards',
                'rewardBreakdown',
            ]
        );
    }

    public ?Collection $rewards = null;

    public ?Collection $rewardBreakdown = null;

    public function calculateRewardTotal()
    {
        return $this->rewardBreakdown?->sum('points') ?? 0;
    }

    public function mapRewards()
    {
        return $this->rewardBreakdown;
    }
}
