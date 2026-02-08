<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->required()
                    ->label('Patient')
                    ->relationship('user' , 'name'),
                TextInput::make('emergency_contact_name')
                    ->default(null),
                TextInput::make('emergency_contact_phone')
                    ->tel()
                    ->default(null),
                TextInput::make('emergency_contact_relationship')
                    ->default(null),
                Select::make('insurance_id')
                    ->label('Insurance')
                    ->relationship('insurance' , 'name')
                    ->default(null),
                DatePicker::make('date_of_birth'),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->default(null),
                Select::make('blood_group')
                    ->options([
                        'A+' => 'A+',
                        'A-' => 'A ',
                        'B+' => 'B+',
                        'B-' => 'B ',
                        'AB+' => 'A b+',
                        'AB-' => 'A b ',
                        'O+' => 'O+',
                        'O-' => 'O ',
                    ])
                    ->default(null),
                TextInput::make('weight')
                    ->numeric()
                    ->default(null),
                TextInput::make('height')
                    ->numeric()
                    ->default(null),
                Toggle::make('email_notifications')
                    ->required(),
                Toggle::make('push_notifications')
                    ->required(),
            ]);
    }
}
