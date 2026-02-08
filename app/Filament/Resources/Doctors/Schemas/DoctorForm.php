<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Doctor')
                    ->required()
                    ->relationship('user', 'name'),
                TextInput::make('license_number')
                    ->required(),
                TextInput::make('experience_years')
                    ->numeric()
                    ->default(null),
                TextInput::make('certification')
                    ->default(null),
                Textarea::make('bio')
                    ->default(null),
                Textarea::make('education')
                    ->default(null),
                TextInput::make('consultation_fee_online')
                    ->numeric()
                    ->default(null),
                TextInput::make('consultation_fee_inperson')
                    ->numeric()
                    ->default(null),
                Select::make('specialization_id')
                    ->required()
                    ->label('Specialization')
                    ->relationship('specialization', 'name'),
                TextInput::make('location')
                    ->default(null),
                Toggle::make('is_verified')
                    ->required(),
                TextInput::make('current_balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
