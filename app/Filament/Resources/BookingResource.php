<?php


namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $recordTitleAttribute = 'consignment_no';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-truck';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Shipment Management';
    }

    protected static ?string $modelLabel = 'Shipment';

    protected static ?string $pluralModelLabel = 'Shipments';

    // Temporarily omit form() override.
    // The runtime error indicates Filament\Forms\Form is not available in this environment.
    // Table + actions can still work.

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('consignment_no')
                    ->label('Consignment #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Consignment number copied!'),
                TextColumn::make('user.name')
                    ->label('Merchant')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('courier_integration.courier_name')
                    ->label('Courier')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('customer_name')
                    ->searchable()
                    ->toggleable()
                    ->limit(20),
                TextColumn::make('destination_city')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('weight')
                    ->suffix(' kg')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('cod_amount')
                    ->label('COD')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 0))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('delivery_charges')
                    ->label('Charges')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 0))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('profit')
                    ->label('Profit')
                    ->getStateUsing(fn (Booking $record): float =>
                        ($record->delivery_charges ?? 0) - (($record->cod_amount ?? 0) * 0.02)
                    )
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 0))
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'picked_up' => 'info',
                        'dispatched' => 'info',
                        'in_transit' => 'primary',
                        'out_for_delivery' => 'warning',
                        'delivered' => 'success',
                        'returned' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('pickup_date')
                    ->label('Pickup')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('delivered_at')
                    ->label('Delivered')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'picked_up' => 'Picked Up',
                        'dispatched' => 'Dispatched',
                        'in_transit' => 'In Transit',
                        'out_for_delivery' => 'Out for Delivery',
                        'delivered' => 'Delivered',
                        'returned' => 'Returned',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
                SelectFilter::make('courier_integration_id')
                    ->label('Courier')
                    ->relationship('courier_integration', 'courier_name')
                    ->multiple(),
                SelectFilter::make('user_id')
                    ->label('Merchant')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->multiple(),
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                    )
                    ->indicateUsing(function (array $data): ?string {
                        if (($data['from'] ?? null) && ($data['until'] ?? null)) {
                            return 'From ' . $data['from'] . ' to ' . $data['until'];
                        }
                        if ($data['from'] ?? null) {
                            return 'From ' . $data['from'];
                        }
                        if ($data['until'] ?? null) {
                            return 'Until ' . $data['until'];
                        }
                        return null;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('view_tracking')
                    ->label('Track')
                    ->icon('heroicon-o-map-pin')
                    ->color('info')
                    ->modalHeading(fn (Booking $record): string => 'Tracking: ' . $record->consignment_no)
                    ->modalContent(fn (Booking $record): string => view('filament.tracking-history', [
                        'booking' => $record,
                        'history' => $record->trackingHistory()->orderBy('created_at', 'desc')->get(),
                    ])->render())
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
                EditAction::make()->label('Edit'),
                Action::make('update_status')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'picked_up' => 'Picked Up',
                                'dispatched' => 'Dispatched',
                                'in_transit' => 'In Transit',
                                'out_for_delivery' => 'Out for Delivery',
                                'delivered' => 'Delivered',
                                'returned' => 'Returned',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Textarea::make('location')
                            ->label('Location / Remarks')
                            ->rows(2),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $oldStatus = $record->status;
                        $record->update(['status' => $data['status']]);

                        if (($data['status'] ?? null) === 'delivered' && !$record->delivered_at) {
                            $record->update(['delivered_at' => now()]);
                        }

                        $record->trackingHistory()->create([
                            'status' => $data['status'],
                            'location' => $data['location'] ?? 'System Update',
                            'description' => 'Status changed from ' . $oldStatus . ' to ' . $data['status'],
                            'updated_by' => auth()->id(),
                        ]);

                        \App\Models\ActivityLog::create([
                            'user_id' => auth()->id(),
                            'loggable_type' => Booking::class,
                            'loggable_id' => $record->id,
                            'event' => 'status_change',
                            'description' => 'Status changed from ' . $oldStatus . ' to ' . $data['status'],
                            'old_values' => ['status' => $oldStatus],
                            'new_values' => ['status' => $data['status']],
                            'ip_address' => request()->ip(),
                        ]);

                        Notification::make()
                            ->title('Status updated successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_status_update')
                        ->label('Change Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'picked_up' => 'Picked Up',
                                    'dispatched' => 'Dispatched',
                                    'in_transit' => 'In Transit',
                                    'out_for_delivery' => 'Out for Delivery',
                                    'delivered' => 'Delivered',
                                    'returned' => 'Returned',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function (Booking $record) use ($data): void {
                                $oldStatus = $record->status;
                                $record->update(['status' => $data['status']]);

                                if (($data['status'] ?? null) === 'delivered' && !$record->delivered_at) {
                                    $record->update(['delivered_at' => now()]);
                                }

                                \App\Models\ActivityLog::create([
                                    'user_id' => auth()->id(),
                                    'loggable_type' => Booking::class,
                                    'loggable_id' => $record->id,
                                    'event' => 'bulk_status_change',
                                    'description' => 'Bulk status update: ' . $oldStatus . ' → ' . $data['status'],
                                    'old_values' => ['status' => $oldStatus],
                                    'new_values' => ['status' => $data['status']],
                                ]);
                            });

                            Notification::make()
                                ->title($records->count() . ' shipments updated to ' . $data['status'])
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('export_selected')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(fn (Collection $records) => \App\Services\ExportService::exportBookings($records)),
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
            'index' => \App\Filament\Resources\BookingResource\Pages\ListBookings::route('/'),
            'create' => \App\Filament\Resources\BookingResource\Pages\CreateBooking::route('/create'),
            'edit' => \App\Filament\Resources\BookingResource\Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}

