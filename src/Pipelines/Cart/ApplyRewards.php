<?php

namespace Hyrograsper\LunarRewards\Pipelines\Cart;

use Closure;
use Hyrograsper\LunarRewards\Facades\Rewards;
use Lunar\Models\Cart;

final class ApplyRewards
{
    public function handle(Cart $cart, Closure $next)
    {
        \Cache::forget(get_class($cart) . $cart->id . '_rewards');

        Rewards::apply($cart);

        return $next($cart);
    }
}
