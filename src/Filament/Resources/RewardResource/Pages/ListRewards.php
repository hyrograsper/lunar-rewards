<?php

namespace Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Hyrograsper\LunarRewards\Filament\Resources\RewardsResource;

class ListRewards extends ListRecords
{
    protected static string $resource = RewardsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
