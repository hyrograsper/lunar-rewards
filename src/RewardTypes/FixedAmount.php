<?php

namespace Hyrograsper\LunarRewards\RewardTypes;

use Hyrograsper\LunarRewards\Base\ValueObjects\Cart\RewardBreakdown;
use Hyrograsper\LunarRewards\Base\ValueObjects\Cart\RewardBreakdownLine;
use Lunar\Models\Collection;
use Lunar\Models\Cart;

class FixedAmount extends AbstractRewardType
{
    /**
     * Return the name of the reward.
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
        $data = $this->reward->data;

        if ($data['fixed_value'] ?? false) {
            return $this->applyFixed(
                values: $data,
                cart: $cart,
            );
        }

        return $this->applyPercentage(
            values: $data,
            cart: $cart,
        );
    }

    protected function applyFixed(array $values, Cart $cart): Cart
    {
        $rewardFactor = $values['reward_qty'] ?? 0;

        $eligibleLines = $this->getEligibleLines($cart);


        $affectedLines = collect();

        $pointsEarned = 0;

        foreach ($eligibleLines as $line) {

            $pointsEarned += $rewardFactor;

            $affectedLines->push(
                new RewardBreakdownLine(
                    line: $line,
                    quantity: $line->quantity
                )
            );
        }

        $this->addRewardBreakdown($cart, new RewardBreakdown(
            points: $pointsEarned,
            lines: $affectedLines,
            reward: $this->reward
        ));

        return $cart;
    }

    protected function applyPercentage(array $values, Cart $cart): Cart
    {
        $rewardFactor = $values['percentage'] ?? 0;

        $eligibleLines = $this->getEligibleLines($cart);

        $affectedLines = collect();

        $pointsEarned = 0;

        foreach ($eligibleLines as $line) {

            $subTotal = $line->subTotal->decimal();

            $pointsEarned += (int) round($subTotal * ($rewardFactor / 100));

            $affectedLines->push(
                new RewardBreakdownLine(
                    line: $line,
                    quantity: $line->quantity
                )
            );
        }

        $this->addRewardBreakdown($cart, new RewardBreakdown(
            points: $pointsEarned,
            lines: $affectedLines,
            reward: $this->reward
        ));

        return $cart;
    }
}
