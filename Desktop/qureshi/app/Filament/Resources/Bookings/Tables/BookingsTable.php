<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('consignment_no')
                    ->label('Consignment No')
                    ->searchable()
                    ->default('-'),
                TextColumn::make('customer_name')
                    ->label('Customer Name')
                    ->searchable(),
                TextColumn::make('customer_phone')
                    ->label('Phone'),
                TextColumn::make('destination_city')
                    ->label('City'),
                TextColumn::make('cod_amount')
                    ->label('COD Amount'),
                TextColumn::make('delivery_charges')
                    ->label('DC'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'dispatched' => 'info',
                        'delivered' => 'success',
                        'returned' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Pehle se mojood standard resources actions load karega
                ...$table->getActions(),
                
                // 🔥 Perfect Running Cancel Button
                \Filament\Actions\Action::make('cancel_booking')
                    ->label('Cancel')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => !empty($record->consignment_no) && $record->status === 'pending' && !str_contains($record->consignment_no, 'Cancelled'))
                    ->action(function ($record) {
                        $courierService = new \App\Models\CourierService();
                        $result = $courierService->cancelParcel($record);

                        if ($result['success']) {
                            $record->update([
                                'status' => 'returned', 
                                'consignment_no' => $record->consignment_no . ' (Cancelled)'
                            ]);

                            Notification::make()
                                ->title('Order Cancelled')
                                ->body('Booking has been cancelled on Courier System.')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Cancellation Failed')
                                ->body($result['message'] ?? 'Error')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                // 🔥 Sahi Core Actions Path For Bulk Grouping
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}