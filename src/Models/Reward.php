<?php

namespace Hyrograsper\LunarRewards\Models;

use Hyrograsper\LunarRewards\Models\Contracts\Reward as RewardContract;
use Hyrograsper\LunarRewards\RewardTypes\AbstractRewardType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Lunar\Base\BaseModel;
use Lunar\Base\Traits\HasChannels;
use Lunar\Base\Traits\HasCustomerGroups;
use Lunar\Base\Traits\HasTranslations;
use Lunar\Models\Brand;
use Lunar\Models\Collection;
use Lunar\Models\CustomerGroup;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

class Reward extends BaseModel implements RewardContract
{
    use HasChannels,
        HasCustomerGroups,
        HasFactory,
        HasTranslations;

    protected $guarded = [];

    const ACTIVE = 'active';

    const PENDING = 'pending';

    const EXPIRED = 'expired';

    const SCHEDULED = 'scheduled';

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'data' => 'array',
    ];

    public function getStatusAttribute(): string
    {
        $active = $this->starts_at?->isPast() && ! $this->ends_at?->isPast();
        $expired = $this->ends_at?->isPast();
        $future = $this->starts_at?->isFuture();

        if ($expired) {
            return static::EXPIRED;
        }

        if ($future) {
            return static::SCHEDULED;
        }

        return $active ? static::ACTIVE : static::PENDING;
    }

    public function users(): BelongsToMany
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(
            config('auth.providers.users.model'),
            "{$prefix}reward_user"
        )->withTimestamps();
    }

    public function purchasables(): HasMany
    {
        return $this->hasMany(RewardPurchasable::modelClass());
    }

    public function purchasableConditions(): HasMany
    {
        return $this->hasMany(RewardPurchasable::modelClass())->whereType('condition');
    }

    public function purchasableExclusions(): HasMany
    {
        return $this->hasMany(RewardPurchasable::modelClass())->whereType('exclusion');
    }

    public function purchasableLimitations(): HasMany
    {
        return $this->hasMany(RewardPurchasable::modelClass())->whereType('limitation');
    }

    public function getType(): AbstractRewardType
    {
        return app($this->type)->with($this);
    }

    public function collections(): BelongsToMany
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(
            Collection::modelClass(),
            "{$prefix}collection_reward"
        )->withPivot(['type'])->withTimestamps();
    }

    public function customerGroups(): BelongsToMany
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(
            CustomerGroup::modelClass(),
            "{$prefix}customer_group_reward"
        )->withPivot([
            'visible',
            'enabled',
            'starts_at',
            'ends_at',
        ])->withTimestamps();
    }

    public function brands(): BelongsToMany
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(
            Brand::modelClass(),
            "{$prefix}brand_reward"
        )->withPivot(['type'])->withTimestamps();
    }

    public function hasExclusionsOrLimitations(): bool
    {
        return $this->collections->count() > 0 ||
            $this->brands->count() > 0 ||
            $this->purchasables->count() > 0;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotNull('starts_at')
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            });
    }

    public function scopeProducts(Builder $query, iterable $productIds = [], array|string $types = []): Builder
    {
        if (is_array($productIds)) {
            $productIds = collect($productIds);
        }

        $types = Arr::wrap($types);

        return $query->where(
            fn ($subQuery) => $subQuery->whereDoesntHave('purchasables', fn ($query) => $query->when($types, fn ($query) => $query->whereIn('type', $types)))
                ->orWhereHas('purchasables',
                    fn ($relation) => $relation->whereIn('purchasable_id', $productIds)
                        ->wherePurchasableType((new Product)->getMorphClass())
                        ->when(
                            $types,
                            fn ($query) => $query->whereIn('type', $types)
                        )
                )
        );
    }

    public function scopeProductVariants(Builder $query, iterable $variantIds = [], array|string $types = []): Builder
    {
        if (is_array($variantIds)) {
            $variantIds = collect($variantIds);
        }

        $types = Arr::wrap($types);

        return $query->where(
            fn ($subQuery) => $subQuery->whereDoesntHave('purchasables', fn ($query) => $query->when($types, fn ($query) => $query->whereIn('type', $types)))
                ->orWhereHas('purchasables',
                    fn ($relation) => $relation->whereIn('purchasable_id', $variantIds)
                        ->wherePurchasableType((new ProductVariant)->getMorphClass())
                        ->when(
                            $types,
                            fn ($query) => $query->whereIn('type', $types)
                        )
                )
        );
    }

    public function scopeUsable(Builder $query): Builder
    {
        return $query->where(function ($subQuery) {
            $subQuery->whereRaw('uses < max_uses')
                ->orWhereNull('max_uses');
        });
    }

    public function scopeCurrent(Builder $builder): Builder
    {
        return $builder->whereHas('collections.channels', function ($query) {
            return $query
                ->whereEnabled(true)
                ->where('starts_at', '<=', now())
                ->where(function ($query) {
                    $query
                        ->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                });
        });
    }
}
