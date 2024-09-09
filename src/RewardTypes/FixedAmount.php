<?php

namespace Hyrograsper\LunarRewards\RewardTypes;

use Lunar\Models\Cart;

class FixedAmount extends AbstractRewardType
{
    /**
     * Return the name of the discount.
     */
    public function getName(): string
    {
        return 'Fixed Amount';
    }

    /**
     * Called just before cart totals are calculated.
     */
    public function apply(Cart $cart): Cart
    {
        return $cart;
    }
}
