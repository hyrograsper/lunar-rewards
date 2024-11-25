<?php

namespace Hyrograsper\LunarRewards\Pipelines\Cart;

use Closure;
use Hyrograsper\LunarRewards\Facades\Rewards;
use Lunar\Models\Cart;

final class ApplyRewards
{
    /**
     * @return mixed
     */
    public function handle(Cart $cart, Closure $next)
    {
        $cart->rewards_cart->rewards = collect();
        $cart->rewards_cart->rewardBreakdown = collect();

        Rewards::apply($cart->rewards_cart);

        return $next($cart);
    }
}
