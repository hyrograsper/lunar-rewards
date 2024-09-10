<?php

namespace Hyrograsper\LunarRewards\Pipelines\Order;

use Closure;
use Hyrograsper\LunarRewards\Facades\Rewards;
use Lunar\Models\Cart;

final class ApplyRewards
{
    public function handle(Cart $cart, Closure $next)
    {
        $cart->rewards = collect([]);
        $cart->rewardBreakdown = collect([]);

        Rewards::apply($cart);

        return $next($cart);
    }
}
