<?php

namespace Hyrograsper\LunarRewards\RewardTypes;

use Hyrograsper\LunarRewards\Base\ValueObjects\Cart\RewardBreakdown;
use Hyrograsper\LunarRewards\Base\ValueObjects\Cart\RewardBreakdownLine;
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
        $data = $this->reward->data;

        if (! $data['min_qty']) {
            return $cart;
        }

        $requiredSpend = (int) $data['min_qty'];

        $rewardQty = $data['reward_qty'];

        $eligibleLines = $this->getEligibleLines($cart);

        $affectedLines = collect();

        $pointsEarned = 0;

        foreach ($eligibleLines as $line) {
            if ($line->quantity >= $requiredSpend) {
                $eligibleFactor = collect(range(0, $line->quantity, $requiredSpend))
                    ->reject(fn ($i) => $i == 0)
                    ->count();

                $affectedLines->push(
                    new RewardBreakdownLine(
                        line: $line,
                        quantity: $line->quantity
                    )
                );

                $pointsEarned += $eligibleFactor * $rewardQty;

            }
        }

        $this->addRewardBreakdown($cart, new RewardBreakdown(
            points: $pointsEarned,
            lines: $affectedLines,
            reward: $this->reward
        ));

        return $cart;
    }
}
