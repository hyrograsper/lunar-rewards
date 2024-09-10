<?php

namespace Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Pages;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\BrandLimitationRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\CollectionLimitationRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\ProductLimitationRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\ProductVariantLimitationRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardsResource;
use Illuminate\Contracts\Support\Htmlable;
use Lunar\Admin\Support\Pages\BaseEditRecord;

class ManageRewardLimitations extends BaseEditRecord
{
    protected static string $resource = RewardsResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('lunar-rewards::reward.pages.limitations.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('lunar-rewards::reward.pages.limitations.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('lunar::discount-limitations');
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
            RelationGroup::make('Limitations', [
                CollectionLimitationRelationManager::class,
                BrandLimitationRelationManager::class,
                ProductLimitationRelationManager::class,
                ProductVariantLimitationRelationManager::class,
            ]),
        ];
    }
}
