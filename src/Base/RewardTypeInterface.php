<?php

namespace Hyrograsper\LunarRewards\Base;

use Lunar\Models\Cart;
use Lunar\Models\CartLine;

interface RewardTypeInterface
{
    /**
     * Return the name of the discount type.
     */
    public function getName(): string;

    /**
     * Execute and apply the discount if conditions are met.
     *
     * @param Cart $cart
     * @return Cart
     */
    public function apply(Cart $cart): Cart;
}
