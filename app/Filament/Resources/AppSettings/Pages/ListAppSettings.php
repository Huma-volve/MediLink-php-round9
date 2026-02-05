<?php

namespace App\Filament\Resources\AppSettings\Pages;

use App\Filament\Resources\AppSettings\AppSettingResource;
use App\Models\AppSetting;
use Filament\Resources\Pages\ListRecords;

class ListAppSettings extends ListRecords
{
    protected static string $resource = AppSettingResource::class;

    public function mount(): void
    {
        parent::mount();

        $record = AppSetting::query()->first();

        if ($record) {
            $this->redirect(AppSettingResource::getUrl('edit', ['record' => $record]));
            return;
        }
    }
}
