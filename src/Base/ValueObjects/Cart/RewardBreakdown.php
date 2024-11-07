<?php

namespace Hyrograsper\LunarRewards\Base\ValueObjects\Cart;

use Hyrograsper\LunarRewards\Models\Reward;
use Illuminate\Support\Collection;

class RewardBreakdown
{
    public function __construct(
        public int $points,
        public Collection $lines,
        public Reward $reward,
    ) {}

    public function toArray()
    {
        return [
            'points' => $this->points,
            'lines' => $this->lines,
            'reward' => $this->reward,
        ];
    }
}
