<?php

namespace Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RewardsOverviewWidget extends BaseWidget
{
    public ?Model $record = null;

    protected int|string|array $columnSpan = ['sm' => 2];

    protected function getStats(): array
    {
        if (! $this->record) {
            return [];
        }

        $orders = $this->record->orders()
            ->withCount([
                'rewards as current_points_earned' => function ($query) {
                    $query
                        ->select(DB::raw('SUM(points) as current_points_earned'))
                        ->active()
                        ->current();
                },
                'rewards as total_points_earned' => function ($query) {
                    $query
                        ->select(DB::raw('SUM(points) as total_points_earned'));
                },
            ])
            ->get();

        return [
            Stat::make(__('lunar-rewards::widgets.customer.rewards_overview.current'), $orders->sum('current_points_earned')),
            Stat::make(__('lunar-rewards::widgets.customer.rewards_overview.total'), $orders->sum('total_points_earned')),
            Stat::make(__('lunar-rewards::widgets.customer.rewards_overview.redeemed'), 0),
        ];
    }
}
