<?php

namespace Hyrograsper\LunarRewards;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hyrograsper\LunarRewards\Commands\LunarRewardsCommand;

class LunarRewardsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('lunar-rewards')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_lunar_rewards_table')
            ->hasCommand(LunarRewardsCommand::class);
    }
}
