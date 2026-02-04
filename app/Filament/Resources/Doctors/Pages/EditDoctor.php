<?php

namespace App\Filament\Resources\Doctors\Pages;

use App\Filament\Resources\Doctors\DoctorResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditDoctor extends EditRecord
{
    protected static string $resource = DoctorResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $userData = $data['user'] ?? [];
            unset($data['user']);

            $userData['role'] = 'doctor';

            if (empty($userData['password'])) {
                unset($userData['password']);
            } elseif (Hash::needsRehash($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }

            $record->user()->update($userData);
            $record->update($data);

            return $record->refresh();
        });
    }
}
