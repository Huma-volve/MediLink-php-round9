<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Illuminate\Support\Facades\Hash;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // USER FIELDS (saved into users table)
            Section::make('User Account')
                ->relationship('user')
                ->dehydrated()
                ->columns(2)
                ->schema([
                    Hidden::make('role')->default('patient'),

                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->required(fn(string $context): bool => $context === 'create')
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->dehydrateStateUsing(fn(?string $state) => filled($state) ? Hash::make($state) : null)
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->maxLength(30)
                        ->unique(ignoreRecord: true)
                        ->nullable(),

                    // ✅ gender here only
                    Select::make('gender')
                        ->options(['male' => 'Male', 'female' => 'Female'])
                        ->nullable(),

                    Toggle::make('is_active')
                        ->default(true)
                        ->required(),

                    Select::make('language_id')
                        ->relationship('language', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    FileUpload::make('profile_picture')
                        ->image()
                        ->disk('public')
                        ->directory('profile-pictures')
                        ->nullable(),
                ]),

            // PATIENT FIELDS (saved into patients table)
            Section::make('Patient Details')
                ->columns(2)
                ->schema([
                    DatePicker::make('date_of_birth')->nullable(),

                    Select::make('blood_group')
                        ->options([
                            'A+' => 'A+',
                            'A-' => 'A-',
                            'B+' => 'B+',
                            'B-' => 'B-',
                            'AB+' => 'AB+',
                            'AB-' => 'AB-',
                            'O+' => 'O+',
                            'O-' => 'O-',
                        ])
                        ->nullable(),

                    Select::make('insurance_id')
                        ->relationship('insurance', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    TextInput::make('emergency_contact_name')
                        ->maxLength(255)
                        ->nullable(),

                    TextInput::make('emergency_contact_phone')
                        ->maxLength(30)
                        ->nullable(),

                    TextInput::make('emergency_contact_relationship')
                        ->maxLength(255)
                        ->nullable(),
                ]),
        ]);
    }
}
