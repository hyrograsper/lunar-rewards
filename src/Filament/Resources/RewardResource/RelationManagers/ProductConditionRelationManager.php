<?php

namespace Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Lunar\Admin\Support\RelationManagers\BaseRelationManager;
use Lunar\Models\Product;

class ProductConditionRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'purchasables';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('lunar-rewards::reward.relationmanagers.conditions.title');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultTable(Table $table): Table
    {

        return $table
            ->heading(
                __('lunar-rewards::reward.relationmanagers.conditions.title')
            )
            ->description(
                __('lunar-rewards::reward.relationmanagers.conditions.description')
            )
            ->paginated(false)
            ->modifyQueryUsing(
                fn ($query) => $query->whereIn('type', ['condition'])
                    ->wherePurchasableType(Product::class)
                    ->whereHas('purchasable')
            )
            ->headerActions([
                Tables\Actions\CreateAction::make()->form([
                    Forms\Components\MorphToSelect::make('purchasable')
                        ->searchable(true)
                        ->types([
                            Forms\Components\MorphToSelect\Type::make(Product::class)
                                ->titleAttribute('name.en')
                                ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                                    return get_search_builder(Product::class, $search)
                                        ->get()
                                        ->mapWithKeys(fn (Product $record): array => [$record->getKey() => $record->attr('name')])
                                        ->all();
                                }),
                        ]),
                ])->label(
                    __('lunar-rewards::reward.relationmanagers.conditions.actions.attach.label')
                )->mutateFormDataUsing(function (array $data) {
                    $data['type'] = 'condition';

                    return $data;
                }),
            ])->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('purchasable.thumbnail')
                    ->collection(config('lunar.media.collection'))
                    ->conversion('small')
                    ->limit(1)
                    ->square()
                    ->label(''),
                Tables\Columns\TextColumn::make('purchasable.attribute_data.name')
                    ->label(
                        __('lunar-rewards::reward.relationmanagers.conditions.table.name.label')
                    )
                    ->formatStateUsing(
                        fn (Model $record) => $record->purchasable->attr('name')
                    ),
            ])->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}