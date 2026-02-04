<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $userData = $data['user'] ?? [];
            unset($data['user']);

            // Keep role consistent
            $userData['role'] = 'patient';

            // If password empty, don't overwrite it
            if (empty($userData['password'])) {
                unset($userData['password']);
            } elseif (Hash::needsRehash($userData['password'])) {
                // only if for any reason it came plain text
                $userData['password'] = Hash::make($userData['password']);
            }

            $record->user()->update($userData);
            $record->update($data);

            return $record->refresh();
        });
    }
}
