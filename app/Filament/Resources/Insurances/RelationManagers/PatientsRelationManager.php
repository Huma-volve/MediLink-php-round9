<?php

namespace App\Filament\Resources\Insurances\RelationManagers;

use App\Models\Language;
use App\Models\Patient;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientsRelationManager extends RelationManager
{
    protected static string $relationship = 'patients'; // Insurance::patients()

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Name')->searchable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                TextColumn::make('user.phone')->label('Phone')->searchable()->toggleable(),
                TextColumn::make('blood_group')->label('Blood')->badge()->toggleable(),
                TextColumn::make('date_of_birth')->label('DOB')->date()->toggleable(),
                IconColumn::make('user.is_active')->label('Active')->boolean(),
                TextColumn::make('created_at')->dateTime()->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New patient')
                    ->modalHeading('Create patient')
                    ->modalWidth('7xl')
                    ->schema(self::patientModalSchema(includePassword: true))
                    ->using(function (array $data): Model {
                        return DB::transaction(function () use ($data) {
                            // 1) Create user first
                            $user = User::create([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'password' => Hash::make($data['password']),
                                'role' => 'patient',
                                'phone' => $data['phone'] ?? null,
                                'gender' => $data['gender'] ?? null,
                                'is_active' => $data['is_active'] ?? true,
                                'language_id' => $data['language_id'] ?? null,
                                'profile_picture' => $data['profile_picture'] ?? null,
                            ]);

                            // 2) Create patient through relationship -> insurance_id is auto-filled
                            return $this->getRelationship()->create([
                                'user_id' => $user->id,
                                'date_of_birth' => $data['date_of_birth'] ?? null,
                                'blood_group' => $data['blood_group'] ?? null,
                                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                                'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
                            ]);
                        });
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit patient')
                    ->modalWidth('7xl')
                    ->schema(self::patientModalSchema(includePassword: false))
                    ->mutateRecordDataUsing(function (array $data, Patient $record): array {
                        // bring user fields into same modal
                        $data['name'] = $record->user?->name;
                        $data['email'] = $record->user?->email;
                        $data['phone'] = $record->user?->phone;
                        $data['gender'] = $record->user?->gender;
                        $data['is_active'] = $record->user?->is_active ?? true;
                        $data['language_id'] = $record->user?->language_id;
                        $data['profile_picture'] = $record->user?->profile_picture;

                        return $data;
                    })
                    ->using(function (Patient $record, array $data): Patient {
                        DB::transaction(function () use ($record, $data) {
                            // update user
                            $record->user()->update([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'phone' => $data['phone'] ?? null,
                                'gender' => $data['gender'] ?? null,
                                'is_active' => $data['is_active'] ?? true,
                                'language_id' => $data['language_id'] ?? null,
                                'profile_picture' => $data['profile_picture'] ?? $record->user?->profile_picture,
                                'role' => 'patient',
                            ]);

                            // update password only if filled
                            if (! empty($data['password'] ?? null)) {
                                $record->user()->update([
                                    'password' => Hash::make($data['password']),
                                ]);
                            }

                            // update patient (insurance_id remains this insurance unless you change it elsewhere)
                            $record->update([
                                'date_of_birth' => $data['date_of_birth'] ?? null,
                                'blood_group' => $data['blood_group'] ?? null,
                                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                                'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
                            ]);
                        });

                        return $record->refresh();
                    }),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function patientModalSchema(bool $includePassword = true): array
    {
        return [
            Section::make('User')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),

                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->maxLength(255),

                    TextInput::make('password')
                        ->password()
                        ->required($includePassword)
                        // on edit: only send password if user typed it
                        ->dehydrated(fn($state) => $includePassword ? true : filled($state)),

                    TextInput::make('phone')->tel()->maxLength(30)->nullable(),

                    Select::make('gender')
                        ->options(['male' => 'Male', 'female' => 'Female'])
                        ->nullable(),

                    Toggle::make('is_active')->default(true),

                    Select::make('language_id')
                        ->options(fn() => Language::query()->orderBy('name')->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    FileUpload::make('profile_picture')
                        ->image()
                        ->disk('public')
                        ->directory('profile-pictures')
                        ->nullable(),
                ]),

            Section::make('Patient')
                ->columns(2)
                ->schema([
                    DatePicker::make('date_of_birth')->nullable(),

                    Select::make('blood_group')
                        ->options([
                            'A+' => 'A+',
                            'A-' => 'A-',
                            'B+' => 'B+',
                            'B-' => 'B-',
                            'AB+' => 'AB+',
                            'AB-' => 'AB-',
                            'O+' => 'O+',
                            'O-' => 'O-',
                        ])
                        ->nullable(),

                    TextInput::make('emergency_contact_name')->maxLength(255)->nullable(),
                    TextInput::make('emergency_contact_phone')->tel()->maxLength(30)->nullable(),
                    TextInput::make('emergency_contact_relationship')->maxLength(255)->nullable(),
                ]),
        ];
    }
}
