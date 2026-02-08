<?php

namespace App\Filament\Resources\Doctors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;

class DoctorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Doctor')
                    ->searchable(),
                TextColumn::make('license_number')
                    ->searchable(),
                TextColumn::make('experience_years')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('certification')
                //     ->searchable(),
                // TextColumn::make('consultation_fee_online')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('consultation_fee_inperson')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('specialization.name')
                    ->label('Specialization')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                IconColumn::make('is_verified')
                    ->boolean(),
                // TextColumn::make('current_balance')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
