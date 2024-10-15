<?php

namespace Hyrograsper\LunarRewards;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentIcon;
use Hyrograsper\LunarRewards\Base\RewardManagerInterface;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Widgets\RewardsOverviewWidget;
use Hyrograsper\LunarRewards\Filament\Resources\RewardsResource;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\PageExtensions\Customer\ViewExtension;
use Hyrograsper\LunarRewards\Managers\RewardManager;
use Hyrograsper\LunarRewards\Models\Reward;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Admin\Filament\Resources\CustomerResource\Pages\ViewCustomer;
use Lunar\Models\Brand;
use Lunar\Models\Collection;
use Lunar\Models\Order;
use Lunar\Models\Product;

class LunarRewards implements Plugin
{
    public function getId(): string
    {
        return 'rewards';
    }

    public function register(Panel $panel): void
    {
        app()->singleton(RewardManagerInterface::class, function ($app) {
            return $app->make(RewardManager::class);
        });

        $panel->resources([
            RewardsResource::class,
        ]);

        $panel->widgets([
            RewardsOverviewWidget::class
        ]);

        Brand::resolveRelationUsing('rewards', function (Brand $brand) {
            $prefix = config('lunar.database.table_prefix');
            return $brand->belongsToMany(Reward::class, $prefix.'brand_reward');
        });

        Collection::resolveRelationUsing('rewards', function (Collection $collection) {
            $prefix = config('lunar.database.table_prefix');
            return $collection->belongsToMany(Reward::class, $prefix.'collection_reward');
        });

        Order::resolveRelationUsing('rewards', function (Order $order) {
            $prefix = config('lunar.database.table_prefix');
            return $order->belongsToMany(Reward::class, $prefix.'order_reward')
                ->withTimestamps();
        });

        Product::resolveRelationUsing('rewards', function (Product $product) {
            $prefix = config('lunar.database.table_prefix');
            return $product->belongsToMany(Reward::class, $prefix.'reward_purchasables', 'purchasable_id','reward_id')
                ->where('purchasable_type', '=', 'product')
                ->withTimestamps();
        });

        LunarPanel::extensions([
            ViewCustomer::class => ViewExtension::class
        ]);

        FilamentIcon::register([
            'lunar-rewards::rewards' => 'lucide-gem'
        ]);
    }

    public function boot(Panel $panel): void
    {

    }

    public static function make(): static
    {
        return app(static::class);
    }
}
