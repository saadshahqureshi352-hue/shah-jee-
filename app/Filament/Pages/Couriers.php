<?php

namespace App\Filament\Pages;

use App\Models\Courier;
use Filament\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Couriers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Couriers';

protected string $view = 'filament.pages.couriers';

    public function table(Table $table): Table
    {
        return $table
            ->query(Courier::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('courier_rate')
                    ->suffix('Rs')
                    ->sortable(),
                TextColumn::make('merchant_rate')
                    ->suffix('Rs')
                    ->sortable(),
                ToggleColumn::make('status')
                    ->label('Active')
                    ->onIcon('heroicon-s-check')
                    ->offIcon('heroicon-s-x')
                    ->onColor('success')
                    ->offColor('danger')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}

