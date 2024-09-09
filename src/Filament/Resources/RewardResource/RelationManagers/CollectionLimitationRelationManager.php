<?php

namespace Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Lunar\Admin\Support\RelationManagers\BaseRelationManager;

class CollectionLimitationRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'collections';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultTable(Table $table): Table
    {

        return $table
            ->description(
                __('lunar-rewards::reward.relationmanagers.collections.description')
            )
            ->paginated(false)
            ->headerActions([
                Tables\Actions\AttachAction::make()->form(fn (Tables\Actions\AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Select::make('type')
                        ->options(
                            fn () => [
                                'limitation' => __('lunar-rewards::reward.relationmanagers.collections.form.type.options.limitation.label'),
                                'exclusion' => __('lunar-rewards::reward.relationmanagers.collections.form.type.options.exclusion.label'),
                            ]
                        )->default('limitation'),
                ])->recordTitle(function ($record) {
                    return $record->attr('name');
                })->preloadRecordSelect()
                    ->label(
                        __('lunar-rewards::reward.relationmanagers.collections.actions.attach.label')
                    ),
            ])->columns([
                Tables\Columns\TextColumn::make('attribute_data.name')
                    ->label(
                        __('lunar-rewards::reward.relationmanagers.collections.table.name.label')
                    )
                    ->formatStateUsing(
                        fn (Model $record) => $record->attr('name')
                    ),
                Tables\Columns\TextColumn::make('pivot.type')
                    ->label(
                        __('lunar-rewards::reward.relationmanagers.collections.table.type.label')
                    )->formatStateUsing(
                        fn (string $state) => __("lunar-rewards::reward.relationmanagers.collections.table.type.{$state}.label")
                    ),
            ])->actions([
                Tables\Actions\DetachAction::make(),
            ]);
    }
}
