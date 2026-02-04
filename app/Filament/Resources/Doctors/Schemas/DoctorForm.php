<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Illuminate\Support\Facades\Hash;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // USER FIELDS (saved into users table)
            Section::make('User Account')
                ->description('User Account Information')
                ->collapsible(true)
                ->relationship('user')
                ->dehydrated()
                ->columns(2)
                ->schema([
                    Hidden::make('role')->default('doctor'),

                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        //making custom validation if it not in the documentation
                        ->rule('min:1')
                        // check if the value is in the array
                        ->in('doctor1', 'doctor2', 'doctor3'),

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

                    DateTimePicker::make('email_verified_at')
                        ->nullable(),
                ])->columnSpan(2),

            // DOCTOR FIELDS (saved into doctors table)
            Section::make('Doctor Details')
                ->description('Doctor Details Information')
                ->collapsible(true)
                ->columns(2)
                ->schema([
                    TextInput::make('license_number')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Select::make('specialization_id')
                        ->relationship('specialization', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('experience_years')
                        ->numeric()
                        ->minValue(0)
                        ->nullable(),

                    TextInput::make('certification')
                        ->maxLength(255)
                        ->nullable(),

                    TextInput::make('consultation_fee_online')
                        ->numeric()
                        ->minValue(0)
                        ->nullable(),

                    TextInput::make('consultation_fee_inperson')
                        ->numeric()
                        ->minValue(0)
                        ->nullable(),

                    TextInput::make('location')
                        ->maxLength(255)
                        ->nullable(),

                    Toggle::make('is_verified')
                        ->default(false),

                    TextInput::make('current_balance')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->disabled()
                        ->columnSpanFull(),

                    Textarea::make('bio')
                        ->rows(4)
                        ->columnSpanFull()
                        ->nullable(),

                    Textarea::make('education')
                        ->rows(4)
                        ->columnSpanFull()
                        ->nullable(),
                ])->columnSpan(
                    [
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ]
                ),
        ]);
    }
}
