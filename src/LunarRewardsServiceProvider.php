<?php

namespace Hyrograsper\LunarRewards;

use Hyrograsper\LunarRewards\Base\RewardManagerInterface;
use Hyrograsper\LunarRewards\Managers\RewardManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hyrograsper\LunarRewards\Commands\LunarRewardsCommand;

class LunarRewardsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        //TODO  This should go somewhere else
        $this->app->singleton(RewardManagerInterface::class, function ($app) {
            return $app->make(RewardManager::class);
        });

        $package
            ->name('lunar-rewards')
            ->hasConfigFile()
            ->hasMigration('create_rewards_table')
            ->hasMigration('create_brand_reward_table')
            ->hasMigration('create_cart_line_reward_table')
            ->hasMigration('create_customer_group_reward_table')
            ->hasMigration('create_reward_collections_table')
            ->hasMigration('create_reward_purchasables_table')
            ->hasMigration('create_reward_user_table');
    }
}
