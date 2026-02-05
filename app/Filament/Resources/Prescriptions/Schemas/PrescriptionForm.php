<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class PrescriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Select::make('appointment_id')
                //     ->relationship('appointment', 'id')
                //     ->required()
                //     ->searchable(),

                Select::make('appointment_id')
                    ->relationship('appointment', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => "موعد رقم: {$record->id} - مريض: {$record->patient?->name}")
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('prescription_number')
                    ->required(),
                Textarea::make('medications')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('frequency')
                    ->required(),
                TextInput::make('duration_days')
                    ->required()
                    ->numeric(),
                Textarea::make('additional_notes')
                    ->default(null)
                    ->columnSpanFull(),
                // Textarea::make('diagnosis')
                //     ->default(null)
                //     ->columnSpanFull(),
                Textarea::make('diagnosis')
                    ->label('diagnosis')
                    ->required(),
                Textarea::make('patient_conditions')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('prescription_date')
                    ->required(),
                DatePicker::make('expiry_date'),


            ]);
    }
}
