<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\TableViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Shipment Details')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Merchant')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('courier_integration_id')
                            ->label('Courier')
                            ->relationship('courier_integration', 'courier_name')
                            ->searchable()
                            ->required(),
                        TextInput::make('consignment_no')
                            ->label('Consignment #')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('tracking_number')
                            ->label('Tracking #')
                            ->maxLength(50),
                        TextInput::make('reference_no')
                            ->label('Reference #')
                            ->maxLength(50),
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
                            ->default('pending')
                            ->required(),
                        Select::make('service_type')
                            ->options([
                                'standard' => 'Standard',
                                'express' => 'Express',
                                'overnight' => 'Overnight',
                                'same_day' => 'Same Day',
                            ])
                            ->default('standard'),
                        Toggle::make('is_cod')
                            ->label('Cash on Delivery')
                            ->default(true),
                    ]),
                Section::make('Customer Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_name')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('customer_phone')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        TextInput::make('second_phone')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('customer_address')
                            ->label('Address')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('consignee_address')
                            ->label('Consignee Address')
                            ->maxLength(255),
                        TextInput::make('destination_city')
                            ->label('Destination City')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('origin_city')
                            ->label('Origin City')
                            ->maxLength(50),
                    ]),
                Section::make('Package & Charges')
                    ->columns(3)
                    ->schema([
                        TextInput::make('weight')
                            ->numeric()
                            ->suffix('kg')
                            ->default(0)
                            ->required(),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1),
                        TextInput::make('product_name')
                            ->maxLength(100),
                        TextInput::make('cod_amount')
                            ->label('COD Amount (PKR)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('delivery_charges')
                            ->label('Delivery Charges (PKR)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        DatePicker::make('pickup_date')
                            ->label('Pickup Date'),
                        Textarea::make('description')
                            ->columnSpan(2)
                            ->rows(2),
                        Textarea::make('special_instructions')
                            ->columnSpan(2)
                            ->rows(2),
                        Textarea::make('remarks')
                            ->columnSpan(2)
                            ->rows(2),
                    ]),
            ]);
    }

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
            ->recordActions([
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
            ->toolbarActions([
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