<?php

namespace Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Pages;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use Hyrograsper\LunarRewards\Filament\Resources\RewardsResource;
use Illuminate\Contracts\Support\Htmlable;
use Lunar\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use Lunar\Admin\Support\Pages\BaseManageRelatedRecords;
use Lunar\Admin\Support\RelationManagers\ChannelRelationManager;

class ManageRewardAvailability extends BaseManageRelatedRecords
{
    protected static string $resource = RewardsResource::class;

    protected static string $relationship = 'channels';

    public function getTitle(): string|Htmlable
    {
        return __('lunar-rewards::reward.pages.availability.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('lunar-rewards::reward.pages.availability.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('lunar::availability');
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function getRelationManagers(): array
    {
        return [
            RelationGroup::make('Availability', [
                ChannelRelationManager::class,
                CustomerGroupRelationManager::make([
                    'pivots' => [
                        'enabled',
                        'visible',
                    ],
                ]),
            ]),
        ];
    }
}
