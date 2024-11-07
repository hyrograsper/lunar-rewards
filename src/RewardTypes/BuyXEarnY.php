<?php

namespace Hyrograsper\LunarRewards\RewardTypes;

use Hyrograsper\LunarRewards\Base\ValueObjects\Cart\RewardBreakdown;
use Hyrograsper\LunarRewards\Base\ValueObjects\Cart\RewardBreakdownLine;
use Hyrograsper\LunarRewards\Models\Cart;
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
        $data = $this->reward->data;

        if (! $data['min_qty']) {
            return $cart;
        }

        $requiredSpend = (int) $data['min_qty'];

        $rewardQty = $data['reward_qty'];

        $eligibleLines = $this->getEligibleLines($cart);

        if (count($eligibleLines) == 0) {
            return $cart;
        }

        $affectedLines = collect();

        $points = 0;

        foreach ($eligibleLines as $line) {

            if ($line->quantity >= $requiredSpend) {
                $eligibleFactor = collect(range(0, $line->quantity, $requiredSpend))
                    ->reject(fn ($i) => $i == 0)
                    ->count();

                $affectedLines->push(
                    new RewardBreakdownLine(
                        lineId: $line->id,
                        quantity: $line->quantity
                    )
                );

                $points += $eligibleFactor * $rewardQty;
            }
        }

        $this->addRewardBreakdown($cart, new RewardBreakdown(
            points: $points,
            lines: $affectedLines,
            reward: $this->reward
        ));

        return $cart;
    }
}
