<?php

namespace Hyrograsper\LunarRewards\RewardTypes;

use Hyrograsper\LunarRewards\Base\RewardTypeInterface;
use Hyrograsper\LunarRewards\Base\ValueObjects\Cart\RewardBreakdown;
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
        $collectionIds = $this->reward->collections->where('pivot.type', 'limitation')->pluck('id');
        $collectionExclusionIds = $this->reward->collections->where('pivot.type', 'exclusion')->pluck('id');

        $brandIds = $this->reward->brands->where('pivot.type', 'limitation')->pluck('id');
        $brandExclusionIds = $this->reward->brands->where('pivot.type', 'exclusion')->pluck('id');

        $productIds = $this->reward->purchasableLimitations
            ->reject(fn($limitation) => !$limitation->purchasable)
            ->map(fn($limitation) => get_class($limitation->purchasable) . '::' . $limitation->purchasable->id);

        $productExclusionIds = $this->reward->purchasableExclusions
            ->reject(fn($limitation) => !$limitation->purchasable)
            ->map(fn($limitation) => get_class($limitation->purchasable) . '::' . $limitation->purchasable->id);

        $lines = $cart->lines;

        if ($collectionIds->count()) {
            $lines = $lines->filter(function ($line) use ($collectionIds) {
                return $line->purchasable->product()->whereHas('collections', function ($query) use ($collectionIds) {
                    $query->whereIn((new \Lunar\Models\Collection)->getTable() . '.id', $collectionIds);
                })->exists();
            });
        }

        if ($collectionExclusionIds->count()) {
            $lines = $lines->reject(function ($line) use ($collectionExclusionIds) {
                return $line->purchasable->product()->whereHas('collections', function ($query) use ($collectionExclusionIds) {
                    $query->whereIn((new Collection)->getTable() . '.id', $collectionExclusionIds);
                })->exists();
            });
        }

        if ($brandIds->count()) {
            $lines = $lines->reject(function ($line) use ($brandIds) {
                return !$brandIds->contains($line->purchasable->product->brand_id);
            });
        }

        if ($brandExclusionIds->count()) {
            $lines = $lines->reject(function ($line) use ($brandExclusionIds) {
                return $brandExclusionIds->contains($line->purchasable->product->brand_id);
            });
        }

        if ($productIds->count()) {
            $lines = $lines->filter(function ($line) use ($productIds) {
                return $productIds->contains(get_class($line->purchasable) . '::' . $line->purchasable->id) || $productIds->contains(get_class($line->purchasable->product) . '::' . $line->purchasable->product->id);
            });
        }

        if ($productExclusionIds->count()) {
            $lines = $lines->reject(function ($line) use ($productExclusionIds) {
                return $productExclusionIds->contains(get_class($line->purchasable) . '::' . $line->purchasable->id) || $productExclusionIds->contains(get_class($line->purchasable->product) . '::' . $line->purchasable->product->id);
            });
        }

        return $lines;
    }

    /**
     * Check if reward's conditions met.
     */
    protected function checkRewardConditions(Cart $cart): bool
    {
        $data = $this->reward->data;

        $cartCoupon = strtoupper($cart->coupon_code ?? '');
        $conditionCoupon = strtoupper($this->reward->coupon ?? '');

        $validCoupon = $cartCoupon ? ($cartCoupon === $conditionCoupon) : blank($conditionCoupon);

        $minSpend = ($data['min_prices'][$cart->currency->code] ?? 0) / $cart->currency->factor;
        $minSpend = (int)bcmul($minSpend, $cart->currency->factor);

        $lines = $this->getEligibleLines($cart);
        $validMinSpend = $minSpend ? $minSpend < $lines->sum('subTotal.value') : true;

        $validMaxUses = $this->reward->max_uses ? $this->reward->uses < $this->reward->max_uses : true;

        if ($validMaxUses && $this->reward->max_uses_per_user) {
            $validMaxUses = $cart->user && ($this->usesByUser($cart->user) < $this->reward->max_uses_per_user);
        }

        return $validCoupon && $validMinSpend && $validMaxUses;
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


    /**
     * Check if discount's conditions met.
     *
     * @param Cart $cart
     * @param RewardBreakdown $breakdown
     * @return self
     */
    protected function addRewardBreakdown(Cart $cart, RewardBreakdown $breakdown)
    {
        $rewardBreakdown = \Cache::get(get_class($cart) . $cart->id . '_rewards') ?? collect();

        $rewardBreakdown->push($breakdown);

        \Cache::put(get_class($cart) . $cart->id . '_rewards', $rewardBreakdown);

        return $this;
    }
}
