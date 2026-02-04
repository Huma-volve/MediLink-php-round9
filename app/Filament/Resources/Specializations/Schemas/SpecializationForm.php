<?php

namespace App\Filament\Resources\Specializations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SpecializationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            TextInput::make('description')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
        ]);
    }
}
