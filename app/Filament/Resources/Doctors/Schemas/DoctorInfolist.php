<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DoctorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('license_number'),
                TextEntry::make('experience_years')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('certification')
                    ->placeholder('-'),
                TextEntry::make('bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('education')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('consultation_fee_online')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('consultation_fee_inperson')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('spelization_id')
                    ->numeric(),
                TextEntry::make('location')
                    ->placeholder('-'),
                IconEntry::make('is_verified')
                    ->boolean(),
                TextEntry::make('current_balance')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
