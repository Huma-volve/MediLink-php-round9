<?php

namespace App\Filament\Resources\Insurances\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InsuranceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
        ]);
    }
}
