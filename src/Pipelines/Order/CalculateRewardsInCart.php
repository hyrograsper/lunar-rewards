<?php

namespace Hyrograsper\LunarRewards\Pipelines\Order;

use Closure;
use Lunar\Models\Order;

final class CalculateRewardsInCart
{
    public function handle(Order $order, Closure $next)
    {
        $rewards = \Cache::get(get_class($order->cart).$order->cart->id.'_rewards');

        $rewards?->each(function ($reward) use ($order) {

            $breakdown = (object) [
                'reward_id' => $reward->reward->id,
                'lines' => $reward->lines->map(function ($line) {
                    return (object) [
                        'quantity' => $line->quantity,
                        'line_id' => $line->line->id,
                    ];
                }),
                'totalPoints' => $reward->points,
            ];

            $order->rewards()->syncWithoutDetaching([
                $reward->reward->id => [
                    'breakdown' => json_encode($breakdown),
                    'points' => $reward->points,
                ],
            ]);
        });

        \Cache::forget(get_class($order->cart).$order->cart->id.'_rewards');

        return $next($order);
    }
}
