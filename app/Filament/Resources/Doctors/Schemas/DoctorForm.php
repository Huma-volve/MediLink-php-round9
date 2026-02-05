<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('license_number')
                    ->required(),
                TextInput::make('experience_years')
                    ->numeric()
                    ->default(null),
                TextInput::make('certification')
                    ->default(null),
                Textarea::make('bio')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('education')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('consultation_fee_online')
                    ->numeric()
                    ->default(null),
                TextInput::make('consultation_fee_inperson')
                    ->numeric()
                    ->default(null),
                // TextInput::make('spelization_id')
                //     ->required()
                //     ->numeric(),
                TextInput::make('location')
                    ->default(null),
                Toggle::make('is_verified')
                    ->required(),
                TextInput::make('current_balance')
                    ->required()
                    ->numeric()
                    ->default(0),

                Select::make('spelization_id')
                    ->label('specialization')
                    ->relationship('specialization', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),





            ]);
    }
}
