<?php

namespace App\Filament\Resources;

use App\Models\Wallet;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Resources\WalletResource\Pages\ListWallets;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-wallet';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Merchant & User Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Wallet Details')
                    ->schema([
                        Select::make('user_id')
                            ->label('Merchant')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        TextInput::make('balance')
                            ->label('Balance (PKR)')
                            ->numeric()
                            ->default(0),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'blocked' => 'Blocked',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Merchant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('balance')
                    ->label('Balance')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                TextColumn::make('total_credited')
                    ->label('Total Credited')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 0))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total_debited')
                    ->label('Total Debited')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 0))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'blocked' => 'danger',
                        'suspended' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'blocked' => 'Blocked',
                        'suspended' => 'Suspended',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('updateBalance')
                    ->label('Update Balance')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->form([
                        TextInput::make('amount')
                            ->label('Amount (PKR)')
                            ->numeric()
                            ->required(),
                        Select::make('type')
                            ->options([
                                'credit' => 'Credit (Add)',
                                'debit' => 'Debit (Subtract)',
                            ])
                            ->required(),
                        Textarea::make('reason')
                            ->label('Reason')
                            ->rows(2)
                            ->required(),
                    ])
                    ->action(function (Wallet $record, array $data): void {
                        if ($data['type'] === 'credit') {
                            $record->balance += $data['amount'];
                            $record->total_credited += $data['amount'];
                        } else {
                            $record->balance -= $data['amount'];
                            $record->total_debited += $data['amount'];
                        }
                        $record->save();

                        \App\Models\ActivityLog::create([
                            'user_id' => auth()->id(),
                            'loggable_type' => Wallet::class,
                            'loggable_id' => $record->id,
                            'event' => 'balance_update',
                            'description' => 'Wallet ' . $data['type'] . ' of PKR ' . $data['amount'] . ' - ' . $data['reason'],
                            'new_values' => ['balance' => $record->balance, $data['type'] => $data['amount']],
                            'ip_address' => request()->ip(),
                        ]);

                        Notification::make()
                            ->title('Balance updated successfully')
                            ->success()
                            ->send();
                    }),
                Action::make('toggleBlock')
                    ->label(fn (Wallet $record): string => $record->status === 'blocked' ? 'Unblock' : 'Block')
                    ->icon(fn (Wallet $record): string => $record->status === 'blocked' ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                    ->color(fn (Wallet $record): string => $record->status === 'blocked' ? 'success' : 'danger')
                    ->action(function (Wallet $record): void {
                        $newStatus = $record->status === 'blocked' ? 'active' : 'blocked';
                        $record->update(['status' => $newStatus]);

                        \App\Models\ActivityLog::create([
                            'user_id' => auth()->id(),
                            'loggable_type' => Wallet::class,
                            'loggable_id' => $record->id,
                            'event' => 'wallet_' . $newStatus,
                            'description' => 'Wallet ' . ($newStatus === 'blocked' ? 'blocked' : 'unblocked'),
                            'new_values' => ['status' => $newStatus],
                        ]);

                        Notification::make()
                            ->title('Wallet ' . ($newStatus === 'blocked' ? 'blocked' : 'unblocked') . ' successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('exportToExcel')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn ($records) => \App\Services\ExportService::export($records, 'wallets')),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWallets::route('/'),
        ];
    }
}