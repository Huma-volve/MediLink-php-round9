<?php

namespace App\Filament\Resources\Insurances;

use App\Filament\Resources\Insurances\Pages\CreateInsurance;
use App\Filament\Resources\Insurances\Pages\EditInsurance;
use App\Filament\Resources\Insurances\Pages\ListInsurances;
use App\Filament\Resources\Insurances\RelationManagers\PatientsRelationManager;
use App\Filament\Resources\Insurances\Schemas\InsuranceForm;
use App\Filament\Resources\Insurances\Tables\InsurancesTable;
use App\Models\Insurance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InsuranceResource extends Resource
{
    protected static ?string $model = Insurance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;
    //protected static ?string $navigationGroup = 'Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return InsuranceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InsurancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PatientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInsurances::route('/'),
            'create' => CreateInsurance::route('/create'),
            'edit'   => EditInsurance::route('/{record}/edit'),
        ];
    }
}
