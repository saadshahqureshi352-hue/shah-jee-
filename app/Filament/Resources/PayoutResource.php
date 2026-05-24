<?php

namespace App\Filament\Resources;

use App\Models\Payout;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PayoutResource\Pages\ListPayouts;

class PayoutResource extends Resource
{
    protected static ?string $model = Payout::class;

    protected static ?string $recordTitleAttribute = 'payout_reference';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-currency-dollar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Financial Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Merchant')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('payout_reference')
                    ->label('Reference #')
                    ->default(fn () => Payout::generateReference())
                    ->required(),
                DatePicker::make('period_start')
                    ->label('Period Start')
                    ->required(),
                DatePicker::make('period_end')
                    ->label('Period End')
                    ->required(),
                TextInput::make('gross_amount')
                    ->label('Gross Amount (PKR)')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $set('net_amount', (float)($state ?? 0) - (float)($get('commissions_deducted') ?? 0) - (float)($get('other_charges') ?? 0))
                    ),
                TextInput::make('commissions_deducted')
                    ->label('Commissions (PKR)')
                    ->numeric()
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $set('net_amount', (float)($get('gross_amount') ?? 0) - (float)($state ?? 0) - (float)($get('other_charges') ?? 0))
                    ),
                TextInput::make('other_charges')
                    ->label('Other Charges (PKR)')
                    ->numeric()
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $set('net_amount', (float)($get('gross_amount') ?? 0) - (float)($get('commissions_deducted') ?? 0) - (float)($state ?? 0))
                    ),
                TextInput::make('net_amount')
                    ->label('Net Amount (PKR)')
                    ->numeric()
                    ->required(),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Select::make('payment_method')
                    ->options([
                        'bank_transfer' => 'Bank Transfer',
                        'cash' => 'Cash',
                        'cheque' => 'Cheque',
                        'wallet' => 'Wallet',
                    ]),
                DatePicker::make('paid_at')
                    ->label('Paid Date'),
                Textarea::make('remarks')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payout_reference')
                    ->label('Reference')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Merchant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gross_amount')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable(),
                TextColumn::make('commissions_deducted')
                    ->label('Commission')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable(),
                TextColumn::make('net_amount')
                    ->label('Net')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable()
                    ->color('success'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'approved' => 'info',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('period_start')
                    ->label('Period')
                    ->formatStateUsing(fn ($record) => $record->period_start?->format('M d, Y') . ' - ' . $record->period_end?->format('M d, Y'))
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),
                Filter::make('period')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('period_start', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('period_end', '<=', $date))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\Action::make('markAsPaid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Payout $record) => $record->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]))
                    ->requiresConfirmation()
                    ->visible(fn (Payout $record): bool => $record->status === 'approved'),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                    \Filament\Tables\Actions\BulkAction::make('exportToExcel')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn ($records) => \App\Services\ExportService::export($records, 'payouts')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayouts::route('/'),
        ];
    }
}