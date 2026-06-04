<?php

namespace App\Filament\Resources\MerchantResource;

use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\MerchantResource\Pages\ListMerchants;
use App\Filament\Resources\MerchantResource\Pages\CreateMerchant;
use App\Filament\Resources\MerchantResource\Pages\EditMerchant;

class MerchantResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Merchant';

    protected static ?string $pluralModelLabel = 'Merchants';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Merchant & User Management';
    }

    protected static ?int $navigationSort = 1;

    // form() override omitted to match project runtime compatibility

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pricingPlan.name')
                    ->label('Rate Plan')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'suspended' => 'warning',
                        'pending' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('wallet_balance')
                    ->label('Wallet Balance')
                    ->formatStateUsing(fn ($state) => $state ? 'PKR ' . number_format($state, 0) : 'PKR 0')
                    ->sortable()
                    ->color(fn ($state) => ($state ?? 0) >= 0 ? 'success' : 'danger')
                    ->toggleable(),
                TextColumn::make('bookings_count')
                    ->label('Total Orders')
                    ->counts('bookings')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('last_login_at')
                    ->label('Last Login')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending Approval',
                    ])
                    ->multiple(),
                SelectFilter::make('is_approved')
                    ->label('Approval Status')
                    ->options([
                        '1' => 'Approved',
                        '0' => 'Not Approved',
                    ]),
                SelectFilter::make('is_vip')
                    ->label('VIP Status')
                    ->options([
                        '1' => 'VIP',
                        '0' => 'Standard',
                    ]),
                SelectFilter::make('pricing_plan_id')
                    ->label('Pricing Plan')
                    ->relationship('pricingPlan', 'name'),
                Filter::make('has_low_wallet')
                    ->label('Low Wallet (Under 1,000)')
                    ->query(fn (Builder $query): Builder => $query->where('wallet_balance', '<', 1000)),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (User $record): bool => !$record->is_approved)
                    ->action(function (User $record): void {
                        $record->update(['is_approved' => true, 'status' => 'active']);

                        \App\Models\ActivityLog::create([
                            'user_id' => auth()->id(),
                            'loggable_type' => User::class,
                            'loggable_id' => $record->id,
                            'event' => 'merchant_approved',
                            'description' => 'Merchant "' . $record->name . '" approved by ' . auth()->user()?->name,
                            'ip_address' => request()->ip(),
                        ]);

                        Notification::make()
                            ->title('Merchant approved successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                Action::make('toggleStatus')
                    ->label(fn (User $record): string => $record->status === 'active' ? 'Suspend' : 'Activate')
                    ->icon(fn (User $record): string => $record->status === 'active' ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                    ->color(fn (User $record): string => $record->status === 'active' ? 'warning' : 'success')
                    ->visible(fn (User $record): bool => $record->is_approved)
                    ->action(function (User $record): void {
                        $newStatus = $record->status === 'active' ? 'suspended' : 'active';
                        $record->update(['status' => $newStatus]);

                        \App\Models\ActivityLog::create([
                            'user_id' => auth()->id(),
                            'loggable_type' => User::class,
                            'loggable_id' => $record->id,
                            'event' => 'merchant_status_change',
                            'description' => 'Merchant "' . $record->name . '" ' . ($newStatus === 'active' ? 'activated' : 'suspended'),
                            'old_values' => ['status' => $record->status],
                            'new_values' => ['status' => $newStatus],
                        ]);

                        Notification::make()
                            ->title('Merchant ' . ($newStatus === 'active' ? 'activated' : 'suspended') . ' successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                Action::make('viewDetails')
                    ->label('Details')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (User $record): string => 'Merchant: ' . $record->name)
                    ->modalContent(function (User $record): string {
                        return view('filament.merchant-details', [
                            'merchant' => $record,
                            'recentBookings' => $record->bookings()->latest()->limit(5)->get(),
                            'walletTransactions' => $record->wallet?->transactions()->latest()->limit(5)->get() ?? collect(),
                        ])->render();
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulkApprove')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records): void {
                            $records->each(function (User $record): void {
                                $record->update(['is_approved' => true, 'status' => 'active']);
                            });
                            Notification::make()
                                ->title($records->count() . ' merchants approved')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('exportToExcel')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(fn (Collection $records) => \App\Services\ExportService::export($records, 'merchants')),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMerchants::route('/'),
            'create' => CreateMerchant::route('/create'),
            'edit' => EditMerchant::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}