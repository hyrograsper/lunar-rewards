<?php

namespace Hyrograsper\LunarRewards\Filament\Resources\RewardResource\PageExtensions\Customer;

use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Widgets\RewardsOverviewWidget;
use Lunar\Admin\Support\Extending\ViewPageExtension;

class ViewExtension extends ViewPageExtension
{
    public function headerWidgets(array $widgets): array
    {
        return [
            ...$widgets,
            RewardsOverviewWidget::make(),
        ];
    }
}
