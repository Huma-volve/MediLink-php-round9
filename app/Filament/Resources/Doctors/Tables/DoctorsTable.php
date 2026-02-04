<?php

namespace App\Filament\Resources\Doctors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DoctorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')->label('Name')->searchable()->sortable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                TextColumn::make('user.phone')->label('Phone')->searchable()->toggleable(),
                TextColumn::make('license_number')->label('License')->searchable()->sortable(),
                TextColumn::make('specialization.name')->label('Specialization')->sortable()->toggleable(),
                IconColumn::make('is_verified')->label('Verified')->boolean(),
                IconColumn::make('user.is_active')->label('Active')->boolean(),
                TextColumn::make('current_balance')->label('Balance')->sortable()->toggleable(),

                TextColumn::make('created_at')
                    //->label will make a name for the column
                    ->dateTime('l d F Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
