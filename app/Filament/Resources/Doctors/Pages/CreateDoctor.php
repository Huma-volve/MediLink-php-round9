<?php

namespace App\Filament\Resources\Doctors\Pages;

use App\Filament\Resources\Doctors\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateDoctor extends CreateRecord
{
    protected static string $resource = DoctorResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $userData = $data['user'] ?? [];
            unset($data['user']);

            // Ensure role
            $userData['role'] = 'doctor';

            if (! empty($userData['password']) && Hash::needsRehash($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }

            $user = User::create($userData);

            $data['user_id'] = $user->id;

            return Doctor::create($data);
        });
    }
}
