<?php

namespace Hyrograsper\LunarRewards\Base\DataTransferObjects;

use Hyrograsper\LunarRewards\Models\Reward;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;

class CartReward
{
    public function __construct(
        public CartLine|Cart $model,
        public Reward $reward
    )
    {
        //
    }
}
