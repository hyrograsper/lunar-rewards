<?php

namespace Hyrograsper\LunarRewards\Managers;

use Hyrograsper\LunarRewards\Base\RewardManagerInterface;
use Hyrograsper\LunarRewards\RewardTypes\BuyXEarnY;
use Hyrograsper\LunarRewards\RewardTypes\FixedAmount;
use Illuminate\Support\Collection;
use Lunar\Base\DataTransferObjects\CartDiscount;
use Lunar\Models\Cart;

class RewardManager implements RewardManagerInterface
{
    protected $types = [
        FixedAmount::class,
        BuyXEarnY::class,
    ];

    /**
     * @inheritDoc
     */
    public function addType($classname): RewardManagerInterface
    {
        // TODO: Implement addType() method.
    }

    /**
     * @inheritDoc
     */
    public function getTypes(): Collection
    {
        return collect($this->types)->map(function ($class) {
            return app($class);
        });
    }

    /**
     * @inheritDoc
     */
    public function addApplied(CartDiscount $cartDiscount): RewardManagerInterface
    {
        // TODO: Implement addApplied() method.
    }

    /**
     * @inheritDoc
     */
    public function getApplied(): Collection
    {
        // TODO: Implement getApplied() method.
    }

    /**
     * @inheritDoc
     */
    public function apply(Cart $cart): Cart
    {
        // TODO: Implement apply() method.
    }

    /**
     * @inheritDoc
     */
    public function validateCoupon(string $coupon): bool
    {
        // TODO: Implement validateCoupon() method.
    }
}
