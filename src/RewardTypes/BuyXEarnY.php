<?php

namespace Hyrograsper\LunarRewards\RewardTypes;

use Lunar\Models\Cart;
use Lunar\Models\CartLine;

class BuyXEarnY extends AbstractRewardType
{
    /**
     * Return the name of the discount.
     */
    public function getName(): string
    {
        return 'Buy X Earn Y';
    }

    /**
     * Called just before cart totals are calculated.
     *
     * @return CartLine
     */
    public function apply(Cart $cart): Cart
    {

        return $cart;
    }
}
