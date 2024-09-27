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
            ->whereHas('rewards', function ($rewards) {
                $rewards
                    ->active()
                    ->current();
            })
            ->withCount(['rewards as points_earned' => function ($query) {
                $query
                    ->select(DB::raw('SUM(points) as points_earned'))
                    ->active()
                    ->current();
            }])
            ->get();

        return [
            Stat::make(__('lunar-rewards::widgets.customer.rewards_overview.label'), $orders->sum('points_earned')),
        ];
    }
}
