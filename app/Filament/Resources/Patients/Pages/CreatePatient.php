<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use App\Models\Patient;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $userData = $data['user'] ?? [];
            unset($data['user']);

            // Ensure role
            $userData['role'] = 'patient';

            // If password came as plain text, hash it (if already hashed, it won't rehash)
            if (! empty($userData['password']) && Hash::needsRehash($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }

            $user = User::create($userData);

            $data['user_id'] = $user->id;

            return Patient::create($data);
        });
    }
}
