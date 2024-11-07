<?php

namespace Hyrograsper\LunarRewards\Base;

use Hyrograsper\LunarRewards\Models\Cart;

interface RewardTypeInterface
{
    /**
     * Return the name of the discount type.
     */
    public function getName(): string;

    /**
     * Execute and apply the discount if conditions are met.
     */
    public function apply(Cart $cart): Cart;
}
