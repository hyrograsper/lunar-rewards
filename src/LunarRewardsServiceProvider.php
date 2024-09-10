<?php

namespace Hyrograsper\LunarRewards;

use Hyrograsper\LunarRewards\Base\RewardManagerInterface;
use Hyrograsper\LunarRewards\Managers\RewardManager;
use Hyrograsper\LunarRewards\Models\Reward;
use Lunar\Models\Brand;
use Lunar\Models\Collection;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LunarRewardsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $this->app->singleton(RewardManagerInterface::class, function ($app) {
            return $app->make(RewardManager::class);
        });

        Brand::resolveRelationUsing('rewards', function (Brand $brand) {
            $prefix = config('lunar.database.table_prefix');

            return $brand->belongsToMany(Reward::class, $prefix.'brand_reward');
        });

        Collection::resolveRelationUsing('rewards', function (Collection $collection) {
            $prefix = config('lunar.database.table_prefix');

            return $collection->belongsToMany(Reward::class, $prefix.'collection_reward');
        });

        //        $this->mergeConfigFrom(__DIR__.'/config/lunar-rewards.php');

        $package
            ->name('lunar-rewards')
            ->hasConfigFile()
            ->hasAssets()
            ->hasTranslations()
            ->hasMigration('create_rewards_table')
            ->hasMigration('create_brand_reward_table')
            ->hasMigration('create_cart_line_reward_table')
            ->hasMigration('create_customer_group_reward_table')
            ->hasMigration('create_reward_collections_table')
            ->hasMigration('create_reward_purchasables_table')
            ->hasMigration('create_reward_user_table');
    }
}
