<?php

namespace Hyrograsper\LunarRewards;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LunarRewardsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('lunar-rewards')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigration('create_rewards_table')
            ->hasMigration('create_brand_reward_table')
            ->hasMigration('create_cart_line_reward_table')
            ->hasMigration('create_customer_group_reward_table')
            ->hasMigration('create_reward_collections_table')
            ->hasMigration('create_reward_purchasables_table')
            ->hasMigration('create_reward_user_table')
            ->hasMigration('create_order_reward_table');
    }
}
