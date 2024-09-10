<?php

namespace Hyrograsper\LunarRewards\RewardTypes;

use Hyrograsper\LunarRewards\Base\RewardTypeInterface;
use Hyrograsper\LunarRewards\Models\Reward;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Lunar\Models\Cart;

abstract class AbstractRewardType implements RewardTypeInterface
{
    /**
     * The instance of the reward.
     */
    public Reward $reward;

    /**
     * Set the data for the reward to user.
     */
    public function with(Reward $reward): self
    {
        $this->reward = $reward;

        return $this;
    }

    /**
     * Mark a reward as used
     */
    public function markAsUsed(Cart $cart): self
    {
        $this->reward->uses = $this->reward->uses + 1;

        if ($user = $cart->user) {
            $this->reward->users()->attach($user);
        }

        return $this;
    }

    /**
     * Return the eligible lines for the reward.
     */
    protected function getEligibleLines(Cart $cart): Collection
    {
        return $cart->lines;
    }

    /**
     * Check if reward's conditions met.
     */
    protected function checkRewardConditions(Cart $cart): bool
    {
        //TODO Implement reward conditions

        return true;
    }

    /**
     * Check how many times this reward has been used by the logged in user's customers
     *
     * @return int
     */
    protected function usesByUser(Authenticatable $user)
    {
        return $this->reward->users()
            ->whereUserId($user->getKey())
            ->count();
    }
}
