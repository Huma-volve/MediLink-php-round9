<?php

namespace App\Filament\Resources\AppSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('company_name')->searchable(),
            TextColumn::make('app_version')->sortable(),
            TextColumn::make('updated_at')->dateTime()->since(),
        ])->recordActions([
            EditAction::make(),
        ]);
    }
}
