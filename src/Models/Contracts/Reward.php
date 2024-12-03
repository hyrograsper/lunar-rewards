<?php

namespace Hyrograsper\LunarRewards\Models\Contracts;

use Hyrograsper\LunarRewards\RewardTypes\AbstractRewardType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Reward
{
    public function users(): BelongsToMany;

    /**
     * Return the reward's purchasables relationship.
     */
    public function purchasables(): HasMany;

    /**
     * Return the reward's purchasable conditions relationship.
     */
    public function purchasableConditions(): HasMany;

    /**
     * Return the reward's purchasable exclusions relationship.
     */
    public function purchasableExclusions(): HasMany;

    /**
     * Return the reward's purchasable limitations relationship.
     */
    public function purchasableLimitations(): HasMany;

    /**
     * Return the reward's type class.
     */
    public function getType(): AbstractRewardType;

    /**
     * Return the reward's collections relationship.
     */
    public function collections(): BelongsToMany;

    /**
     * Return the reward's customer groups relationship.
     */
    public function customerGroups(): BelongsToMany;

    /**
     * Return the reward's brands relationship.
     */
    public function brands(): BelongsToMany;

    /**
     * Return true or false if rewards has attached limitations or exclusions.
     */
    public function hasExclusionsOrLimitations(): bool;

    /**
     * Return the active scope.
     */
    public function scopeActive(Builder $query): Builder;

    /**
     * Return the products scope.
     */
    public function scopeProducts(Builder $query, iterable $productIds = [], array|string $types = []): Builder;

    /**
     * Return the product variants scope.
     */
    public function scopeProductVariants(Builder $query, iterable $variantIds = [], array|string $types = []): Builder;

    /**
     * Return when the reward is usable.
     */
    public function scopeUsable(Builder $query): Builder;

    /**
     * Return when the reward is in the current start end timeframe.
     */
    public function scopeCurrent(Builder $query): Builder;
}
