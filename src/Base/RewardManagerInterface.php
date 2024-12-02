<?php

namespace Hyrograsper\LunarRewards\Base;

use Hyrograsper\LunarRewards\Base\DataTransferObjects\CartReward;
use Illuminate\Support\Collection;
use Lunar\Models\Cart;

interface RewardManagerInterface
{
    /**
     * Add a discount type by classname
     *
     * @param  string  $classname
     */
    public function addType($classname): self;

    /**
     * Return the available discount types.
     */
    public function getTypes(): Collection;

    /**
     * Add an applied discount
     */
    public function addApplied(CartReward $cartReward): self;

    /**
     * Return the applied discounts
     */
    public function getApplied(): Collection;

    /**
     * Apply discounts for a given cart.
     */
    public function apply(Cart $cart): Cart;
}
