<?php

namespace Hyrograsper\LunarRewards\Pipelines\Order;

use Closure;
use Lunar\Models\Order;

final class CalculateRewardsInCart
{
    public function handle(Order $order, Closure $next)
    {
        $cart = $order->cart->recalculate();

        $cart->rewards_cart->rewardBreakdown?->each(function ($reward) use ($order) {

            $breakdown = (object) [
                'reward_id' => $reward->reward->id,
                'lines' => $reward->lines->map(function ($line) {
                    return (object) [
                        'quantity' => $line->quantity,
                        'line_id' => $line->lineId,
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

        return $next($order);
    }
}
