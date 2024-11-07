<?php

namespace Hyrograsper\LunarRewards\Filament\Resources;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hyrograsper\LunarRewards\Base\LunarPanelRewardInterface;
use Hyrograsper\LunarRewards\Facades\Rewards;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Pages\EditRewards;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Pages\ListRewards;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Pages\ManageRewardAvailability;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\Pages\ManageRewardLimitations;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\BrandLimitationRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\CollectionLimitationRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\ProductConditionRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\ProductLimitationRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\ProductRewardRelationManager;
use Hyrograsper\LunarRewards\Filament\Resources\RewardResource\RelationManagers\ProductVariantLimitationRelationManager;
use Hyrograsper\LunarRewards\Models\Reward;
use Hyrograsper\LunarRewards\RewardTypes\BuyXEarnY;
use Hyrograsper\LunarRewards\RewardTypes\FixedAmount;
use Hyrograsper\LunarRewards\RewardTypes\SpendXEarnY;
use Illuminate\Support\Str;
use Lunar\Admin\Support\Resources\BaseResource;
use Lunar\Models\Currency;

class RewardsResource extends BaseResource
{
    protected static ?string $model = Reward::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    protected static ?int $navigationSort = 3;

    public static function getLabel(): string
    {
        return __('lunar-rewards::reward.label');
    }

    public static function getPluralLabel(): string
    {
        return __('lunar-rewards::reward.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('lunar-rewards::rewards');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sales';
    }

    public static function getDefaultForm(Form $form): Form
    {
        $rewardSchemas = Rewards::getTypes()->map(function ($reward) {
            if (! $reward instanceof LunarPanelRewardInterface) {
                return;
            }

            return Section::make(Str::slug(get_class($reward)))
                ->heading($reward->getName())
                ->visible(
                    fn (Get $get) => $get('type') == get_class($reward)
                )->schema($reward->lunarPanelSchema());
        })->filter();

        return $form->schema([
            Section::make('')->schema(
                static::getMainFormComponents()
            ),
            //            Section::make('conditions')->schema(
            //                static::getConditionsFormComponents()
            //            )->heading(
            //                __('lunar-rewards::reward.form.conditions.heading')
            //            ),
            Section::make('buy_x_earn_y')
                ->heading(
                    __('lunar-rewards::reward.form.buy_x_earn_y.heading')
                )
                ->visible(
                    fn (Get $get) => $get('type') == BuyXEarnY::class
                )->schema(
                    static::getBuyXEarnYFormComponents()
                ),
            Section::make('amount_off')
                ->heading(
                    __('lunar-rewards::reward.form.fixed_amount.heading')
                )
                ->visible(
                    fn (Get $get) => $get('type') == FixedAmount::class
                )->schema(
                    static::getFixedAmountFormComponents()
                ),
            Section::make('spend_x_earn_y')
                ->heading(
                    __('lunar-rewards::reward.form.spend_x_earn_y.heading')
                )
                ->visible(
                    fn (Get $get) => $get('type') == SpendXEarnY::class
                )->schema(
                    static::getSpendXEarnYFormComponents()
                ),
            ...$rewardSchemas,
        ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            Group::make([
                static::getNameFormComponent(),
                static::getHandleFormComponent(),
            ])->columns(2),
            Group::make([
                static::getStartsAtFormComponent(),
                static::getEndsAtFormComponent(),
            ])->columns(2),
            Group::make([
                static::getRewardTypeFormComponent(),
            ])->columns(2),
        ];
    }

    public static function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('lunar-rewards::reward.form.name.label'))
            ->live(onBlur: true)
            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                if ($operation !== 'create') {
                    return;
                }
                $set('handle', Str::slug($state));
            })
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getConditionsFormComponents(): array
    {
        return [
            Group::make([
                static::getCouponFormComponent(),
                static::getMaxUsesFormComponent(),
                static::getMaxUsesPerUserFormComponent(),
            ])->columns(3),
            Fieldset::make()->schema(
                static::getMinimumCartAmountsFormComponents()
            )->label(
                __('lunar-rewards::reward.form.minimum_cart_amount.label')
            ),
        ];
    }

    public static function getHandleFormComponent(): Component
    {
        return TextInput::make('handle')
            ->label(__('lunar-rewards::reward.form.handle.label'))
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength(255)
            ->autofocus();
    }

    public static function getStartsAtFormComponent(): Component
    {
        return DateTimePicker::make('starts_at')
            ->label(__('lunar-rewards::reward.form.starts_at.label'))
            ->required()
            ->before(function (Get $get) {
                return $get('ends_at');
            });
    }

    public static function getEndsAtFormComponent(): Component
    {
        return DateTimePicker::make('ends_at')
            ->label(__('lunar-rewards::reward.form.ends_at.label'));
    }

    protected static function getPriorityFormComponent(): Component
    {
        return Select::make('priority')
            ->label(__('lunar-rewards::reward.form.priority.label'))
            ->helperText(
                __('lunar-rewards::reward.form.priority.helper_text')
            )
            ->options(function () {
                return [
                    1 => __('lunar-rewards::reward.form.priority.options.low.label'),
                    5 => __('lunar-rewards::reward.form.priority.options.medium.label'),
                    10 => __('lunar-rewards::reward.form.priority.options.high.label'),
                ];
            });
    }

    protected static function getStopFormComponent(): Component
    {
        return Toggle::make('stop')
            ->label(
                __('lunar-rewards::reward.form.stop.label')
            );
    }

    protected static function getCouponFormComponent(): Component
    {
        return TextInput::make('coupon')
            ->label(
                __('lunar-rewards::reward.form.coupon.label')
            )->helperText(
                __('lunar-rewards::reward.form.coupon.helper_text')
            );
    }

    protected static function getMaxUsesFormComponent(): Component
    {
        return TextInput::make('max_uses')
            ->label(
                __('lunar-rewards::reward.form.max_uses.label')
            )->helperText(
                __('lunar-rewards::reward.form.max_uses.helper_text')
            );
    }

    protected static function getMaxUsesPerUserFormComponent(): Component
    {
        return TextInput::make('max_uses_per_user')
            ->label(
                __('lunar-rewards::reward.form.max_uses_per_user.label')
            )->helperText(
                __('lunar-rewards::reward.form.max_uses_per_user.helper_text')
            );
    }

    protected static function getMinimumCartAmountsFormComponents(): array
    {
        $currencies = Currency::enabled()->get();
        $inputs = [];

        foreach ($currencies as $currency) {
            $inputs[] = TextInput::make('data.min_prices.'.$currency->code)->label(
                $currency->code
            )->afterStateHydrated(function (TextInput $component, $state) {
                $currencyCode = last(explode('.', $component->getStatePath()));
                $currency = Currency::whereCode($currencyCode)->first();

                if ($currency) {
                    $component->state($state / $currency->factor);
                }
            });
        }

        return $inputs;
    }

    public static function getRewardTypeFormComponent(): Component
    {
        return Select::make('type')->options(
            Rewards::getTypes()->mapWithKeys(
                fn ($type) => [get_class($type) => $type->getName()]
            )
        )->required()->live();
    }

    public static function getBuyXEarnYFormComponents(): array
    {
        return [
            Group::make([
                TextInput::make('data.min_qty')
                    ->label(
                        __('lunar-rewards::reward.form.min_qty.label')
                    )->helperText(
                        __('lunar-rewards::reward.form.min_qty.helper_text')
                    )->numeric(),
                TextInput::make('data.reward_qty')
                    ->label(
                        __('lunar-rewards::reward.form.buy_x_earn_y.reward_qty.label')
                    )->helperText(
                        __('lunar-rewards::reward.form.buy_x_earn_y.reward_qty.helper_text')
                    )->numeric(),
            ])->columns(2),
        ];
    }

    public static function getSpendXEarnYFormComponents(): array
    {
        return [
            Group::make([
                TextInput::make('data.min_qty')
                    ->label(
                        __('lunar-rewards::reward.form.spend_x_earn_y.min_qty.label')
                    )->helperText(
                        __('lunar-rewards::reward.form.spend_x_earn_y.min_qty.helper_text')
                    )->numeric(),
                TextInput::make('data.reward_qty')
                    ->label(
                        __('lunar-rewards::reward.form.spend_x_earn_y.reward_qty.label')
                    )->helperText(
                        __('lunar-rewards::reward.form.spend_x_earn_y.reward_qty.helper_text')
                    )->numeric(),
            ])->columns(2),
        ];
    }

    public static function getFixedAmountFormComponents(): array
    {
        return [
            Toggle::make('data.fixed_value')->default(true)->live(),
            TextInput::make('data.percentage')->visible(
                fn (Get $get) => ! $get('data.fixed_value')
            )->numeric(),
            TextInput::make('data.reward_qty')->label(
                __('lunar-rewards::reward.form.spend_x_earn_y.reward_qty.label')
            )->visible(
                fn (Get $get) => (bool) $get('data.fixed_value')
            )->numeric(),
        ];
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->searchable();
    }

    protected static function getTableColumns(): array
    {
        return [
            TextColumn::make('status')
                ->formatStateUsing(function ($state) {
                    return __("lunar-rewards::reward.table.status.{$state}.label");
                })
                ->label(__('lunar-rewards::reward.table.status.label'))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    Reward::ACTIVE => 'success',
                    Reward::EXPIRED => 'danger',
                    Reward::PENDING => 'gray',
                    Reward::SCHEDULED => 'info',
                }),
            TextColumn::make('name')
                ->label(__('lunar-rewards::reward.table.name.label')),
            TextColumn::make('type')
                ->formatStateUsing(function ($state) {
                    return (new $state)->getName();
                })
                ->label(__('lunar-rewards::reward.table.type.label')),
            TextColumn::make('starts_at')
                ->label(__('lunar-rewards::reward.table.starts_at.label'))
                ->date(),
            TextColumn::make('ends_at')
                ->label(__('lunar-rewards::reward.table.ends_at.label'))
                ->date(),
        ];
    }

    protected static function getDefaultRelations(): array
    {
        return [
            CollectionLimitationRelationManager::class,
            BrandLimitationRelationManager::class,
            ProductLimitationRelationManager::class,
            ProductVariantLimitationRelationManager::class,
            ProductRewardRelationManager::class,
            //            ProductConditionRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => ListRewards::route('/'),
            'edit' => EditRewards::route('/{record}'),
            'limitations' => ManageRewardLimitations::route('/{record/limitations}'),
            'availability' => ManageRewardAvailability::route('/{record/availability}'),
        ];
    }
}
