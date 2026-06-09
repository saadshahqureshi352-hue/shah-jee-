<?php
namespace App\Filament\Pages;

use App\Models\Invoice;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;

class Invoices extends Page implements HasTable
{
    use InteractsWithTable;

    // Filament expects navigationIcon to be BackedEnum|string|null
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-refund';

    protected static ?string $navigationLabel = 'Invoices';

    public function table(Table $table): Table
    {
        return $table
            ->query(Invoice::query()->with('merchant'))
            ->columns([
                TextColumn::make('id')
                    ->label('Invoice #')
                    ->sortable(),

                TextColumn::make('merchant.business_name')
                    ->label('Merchant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('period_date')
                    ->date()
                    ->label('Period')
                    ->sortable(),

                TextColumn::make('total_cod')
                    ->label('Total COD')
                    ->money('PKR')
                    ->sortable(),

                TextColumn::make('delivery_charges_deducted')
                    ->label('Delivery Charges')
                    ->money('PKR')
                    ->sortable(),

                TextColumn::make('tax_deducted')
                    ->label('Tax (4%)')
                    ->money('PKR')
                    ->sortable(),

                TextColumn::make('net_payable')
                    ->label('Net Payable')
                    ->money('PKR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'overdue' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Invoice $record): string => "/admin/api/admin/invoices/{$record->id}/orders", shouldOpenInNewTab: true),

                Action::make('markAsPaid')
                    ->label('Mark as Paid')
                    ->action(fn (Invoice $record) => $record->markAsPaid())
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->visible(fn (Invoice $record) => $record->status === 'pending'),
            ])
            ->bulkActions([]);
    }
}

