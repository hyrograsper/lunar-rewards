<?php

namespace Hyrograsper\LunarRewards;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Hyrograsper\LunarRewards\Filament\Resources\RewardsResource;

class LunarRewards implements Plugin
{
    public function getId(): string
    {
        return 'rewards';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            RewardsResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
