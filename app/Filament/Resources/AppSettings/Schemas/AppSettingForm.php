<?php

namespace App\Filament\Resources\AppSettings\Schemas;

use Filament\Forms\Components\FileUpload;
//use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AppSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Application')
                ->columns(2)
                ->schema([
                    // لو تبي app_name من env فقط (غير قابل للحفظ)
                    TextInput::make('app_name')
                        ->default(env('APP_NAME'))
                        ->required(),

                    // أو لو تبي تخليه من DB (احذف disabled/dehydrated(false))
                    // TextInput::make('app_name')->required(),

                    TextInput::make('app_version')
                        ->required()
                        ->maxLength(20),

                    TextInput::make('company_name')
                        ->required()
                        ->maxLength(255),

                    FileUpload::make('app_logo')
                        ->image()
                        ->disk('public')
                        ->directory('app-settings')
                        ->nullable(),
                ]),

            Section::make('Links')
                ->columns(2)
                ->schema([
                    TextInput::make('terms_url')->url()->nullable(),
                    TextInput::make('privacy_url')->url()->nullable(),
                    TextInput::make('license_url')->url()->nullable(),
                    TextInput::make('release_notes_url')->url()->nullable(),
                ]),

            Section::make('Contact Information')
                ->columns(2)
                ->schema([
                    TextInput::make('support_email')->email()->nullable(),
                    TextInput::make('website_url')->url()->nullable(),
                    TextInput::make('company_address')->columnSpanFull()->nullable(),
                ]),
        ]);
    }
}
