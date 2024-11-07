<?php

namespace Hyrograsper\LunarRewards\Managers;

use Hyrograsper\LunarRewards\Base\DataTransferObjects\CartReward;
use Hyrograsper\LunarRewards\Base\RewardManagerInterface;
use Hyrograsper\LunarRewards\Models\Cart;
use Hyrograsper\LunarRewards\Models\Reward;
use Hyrograsper\LunarRewards\RewardTypes\BuyXEarnY;
use Hyrograsper\LunarRewards\RewardTypes\FixedAmount;
use Hyrograsper\LunarRewards\RewardTypes\SpendXEarnY;
use Illuminate\Support\Collection;
use Lunar\Models\Channel;
use Lunar\Models\CustomerGroup;

class RewardManager implements RewardManagerInterface
{
    protected $types = [
        FixedAmount::class,
        SpendXEarnY::class,
        BuyXEarnY::class,
    ];

    protected Collection $applied;

    protected ?Collection $rewards = null;

    protected ?Collection $channels = null;

    protected ?Collection $customerGroups = null;

    public function __construct()
    {
        $this->applied = collect();
        $this->channels = collect();
        $this->customerGroups = collect();
    }

    public function channel(Channel|iterable $channel): self
    {
        $channels = collect(
            ! is_iterable($channel) ? [$channel] : $channel
        );

        if ($nonChannel = $channels->filter(fn ($channel) => ! $channel instanceof Channel)->first()) {
            throw new InvalidArgumentException(
                __('lunar-rewards::exceptions.rewards.invalid_type', [
                    'expected' => Channel::class,
                    'actual' => $nonChannel->getMorphClass(),
                ])
            );
        }

        $this->channels = $channels;

        return $this;
    }

    public function customerGroup(CustomerGroup|iterable $customerGroups): self
    {
        $customerGroups = collect(
            ! is_iterable($customerGroups) ? [$customerGroups] : $customerGroups
        );

        if ($nonGroup = $customerGroups->filter(fn ($channel) => ! $channel instanceof CustomerGroup)->first()) {
            throw new InvalidArgumentException(
                __('lunar::exceptions.rewards.invalid_type', [
                    'expected' => CustomerGroup::class,
                    'actual' => $nonGroup->getMorphClass(),
                ])
            );
        }
        $this->customerGroups = $customerGroups;

        return $this;
    }

    public function getRewards(?Cart $cart = null)
    {
        if ($this->channels->isEmpty() && $defaultChannel = Channel::getDefault()) {
            $this->channel($defaultChannel);
        }

        if ($this->customerGroups->isEmpty() && $defaultGroup = CustomerGroup::getDefault()) {
            $this->customerGroup($defaultGroup);
        }

        return Reward::active()
            ->usable()
            ->channel($this->channels)
            ->customerGroup($this->customerGroups)
            ->with(['purchasables'])
            ->when(
                $cart,
                function ($query, $value) {
                    return $query->where(function ($query) use ($value) {

                        return $query->where(fn ($query) => $query->products(
                            $value->lines->pluck('purchasable.product_id')->filter()->values(),
                            ['condition', 'limitation']
                        )
                        )
                            ->orWhere(fn ($query) => $query->productVariants(
                                $value->lines->pluck('purchasable.id')->filter()->values(),
                                ['condition', 'limitation']
                            )
                            );
                    });
                }
            )
            ->when(
                $cart?->coupon_code,
                function ($query, $value) {
                    return $query->where(function ($query) use ($value) {
                        $query->where('coupon', $value)
                            ->orWhereNull('coupon')
                            ->orWhere('coupon', '');
                    });
                },
                fn ($query, $value) => $query->whereNull('coupon')->orWhere('coupon', '')
            )->orderBy('priority', 'desc')
            ->orderBy('id')
            ->get();
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * {@inheritDoc}
     */
    public function addType($classname): RewardManagerInterface
    {
        $this->types[] = $classname;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTypes(): Collection
    {
        return collect($this->types)->map(function ($class) {
            return app($class);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function addApplied(CartReward $cartReward): RewardManagerInterface
    {
        $this->applied->push($cartReward);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getApplied(): Collection
    {
        return $this->applied;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Cart $cart): Cart
    {
        if (! $this->rewards || $this->rewards?->isEmpty()) {
            $this->rewards = $this->getRewards($cart);
        }

        foreach ($this->rewards as $reward) {
            $cart = $reward->getType()->apply($cart);
        }

        return $cart;
    }
}
