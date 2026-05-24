<?php

namespace App\Filament\Resources\ActivityLogResource;

use App\Models\ActivityLog;
use Filament\Resources\Resource;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\KeyValueEntry;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $recordTitleAttribute = 'description';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-text';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    protected static ?string $slug = 'activity-logs';

    // Filament expects form() signature matching Resource::form(Schema $schema): Schema.
    // Activity logs are read-only, so we omit the form() override.



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        'status_change' => 'warning',
                        'bulk_status_change' => 'warning',
                        'balance_update' => 'info',
                        'wallet_blocked' => 'danger',
                        'wallet_active' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),
                TextColumn::make('loggable_type')
                    ->label('Module')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : 'N/A')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('description')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn (ActivityLog $record): ?string => $record->description),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Date/Time')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                        'status_change' => 'Status Change',
                        'balance_update' => 'Balance Update',
                        'wallet_blocked' => 'Wallet Blocked',
                        'wallet_active' => 'Wallet Active',
                    ])
                    ->multiple(),
                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable(),
                Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from'),
                        \Filament\Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()
                    ->infolist(fn (Infolist $infolist): Infolist => $infolist
                        ->schema([
                            Section::make('Activity Details')
                                ->columns(2)
                                ->schema([
                                    TextEntry::make('user.name')
                                        ->label('Performed By'),
                                    TextEntry::make('event')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'created' => 'success',
                                            'updated' => 'info',
                                            'deleted' => 'danger',
                                            'status_change' => 'warning',
                                            default => 'gray',
                                        }),
                                    TextEntry::make('loggable_type')
                                        ->label('Module')
                                        ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : 'N/A'),
                                    TextEntry::make('loggable_id')
                                        ->label('Record ID'),
                                    TextEntry::make('description')
                                        ->columnSpan(2),
                                    TextEntry::make('ip_address')
                                        ->label('IP Address'),
                                    TextEntry::make('user_agent')
                                        ->label('User Agent')
                                        ->columnSpan(2),
                                    TextEntry::make('created_at')
                                        ->label('Date/Time')
                                        ->dateTime('M d, Y H:i:s'),
                                ]),
                            Section::make('Changes')
                                ->schema([
                                    KeyValueEntry::make('old_values')
                                        ->label('Old Values'),
                                    KeyValueEntry::make('new_values')
                                        ->label('New Values'),
                                ]),
                        ])
                    ),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('exportToExcel')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn ($records) => \App\Services\ExportService::export($records, 'activity-logs')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ActivityLogResource\Pages\ListActivityLogs::route('/'),
        ];
    }
}