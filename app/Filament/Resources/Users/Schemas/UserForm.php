<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'doctor' => 'Doctor', 'patient' => 'Patient'])
                    ->default('doctor')
                    ->required(),
                TextInput::make('profile_picture')
                    ->default(null),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
            ]);
    }
}
