<?php

namespace App\Filament\Resources\Specializations\RelationManagers;

use App\Models\Doctor;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorsRelationManager extends RelationManager
{
    protected static string $relationship = 'doctors'; // Specialization::doctors()

    // IMPORTANT if you want create/edit on the View page too:
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Name')->searchable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                TextColumn::make('license_number')->searchable(),
                TextColumn::make('experience_years')->sortable(),
                TextColumn::make('location')->searchable(),
                IconColumn::make('is_verified')->boolean(),
                TextColumn::make('current_balance')->sortable(),
                TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New doctor')
                    ->modalHeading('Create doctor')
                    ->modalWidth('7xl')
                    ->schema(self::doctorModalSchema(includePassword: true))
                    ->using(function (array $data): Model {
                        return DB::transaction(function () use ($data) {
                            $user = User::create([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'password' => Hash::make($data['password']),
                                'role' => 'doctor',
                                'gender' => $data['gender'] ?? null,
                                'phone' => $data['phone'] ?? null,
                                'is_active' => $data['is_active'] ?? true,
                            ]);

                            // Because this is a relation manager, this will auto-set specialization_id:
                            return $this->getRelationship()->create([
                                'user_id' => $user->id,
                                'license_number' => $data['license_number'],
                                'experience_years' => $data['experience_years'] ?? null,
                                'certification' => $data['certification'] ?? null,
                                'bio' => $data['bio'] ?? null,
                                'education' => $data['education'] ?? null,
                                'consultation_fee_online' => $data['consultation_fee_online'] ?? null,
                                'consultation_fee_inperson' => $data['consultation_fee_inperson'] ?? null,
                                'location' => $data['location'] ?? null,
                                'is_verified' => $data['is_verified'] ?? false,
                                'current_balance' => $data['current_balance'] ?? 0,
                            ]);
                        });
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit doctor')
                    ->modalWidth('7xl')
                    ->schema(self::doctorModalSchema(includePassword: false))
                    ->mutateRecordDataUsing(function (array $data, Doctor $record): array {
                        // Fill user fields into the same modal:
                        $data['name'] = $record->user?->name;
                        $data['email'] = $record->user?->email;
                        $data['gender'] = $record->user?->gender;
                        $data['phone'] = $record->user?->phone;
                        $data['is_active'] = $record->user?->is_active ?? true;

                        return $data;
                    })
                    ->using(function (Doctor $record, array $data): Doctor {
                        DB::transaction(function () use ($record, $data) {
                            // Update User
                            $record->user()->update([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'gender' => $data['gender'] ?? null,
                                'phone' => $data['phone'] ?? null,
                                'is_active' => $data['is_active'] ?? true,
                            ]);

                            // Only update password if it was filled:
                            if (! empty($data['password'] ?? null)) {
                                $record->user()->update([
                                    'password' => Hash::make($data['password']),
                                ]);
                            }

                            // Update Doctor
                            $record->update([
                                'license_number' => $data['license_number'],
                                'experience_years' => $data['experience_years'] ?? null,
                                'certification' => $data['certification'] ?? null,
                                'bio' => $data['bio'] ?? null,
                                'education' => $data['education'] ?? null,
                                'consultation_fee_online' => $data['consultation_fee_online'] ?? null,
                                'consultation_fee_inperson' => $data['consultation_fee_inperson'] ?? null,
                                'location' => $data['location'] ?? null,
                                'is_verified' => $data['is_verified'] ?? false,
                                'current_balance' => $data['current_balance'] ?? 0,
                            ]);
                        });

                        return $record;
                    }),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function doctorModalSchema(bool $includePassword = true): array
    {
        return [
            Section::make('User')
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
                        // when editing: don’t send password unless user typed it
                        ->dehydrated(fn($state) => $includePassword ? true : filled($state)),

                    Select::make('gender')
                        ->options(['male' => 'Male', 'female' => 'Female'])
                        ->nullable(),

                    TextInput::make('phone')->nullable(),

                    Toggle::make('is_active')->default(true),
                ])
                ->columns(2),

            Section::make('Doctor')
                ->schema([
                    TextInput::make('license_number')->required()->maxLength(255),

                    TextInput::make('experience_years')->numeric()->minValue(0)->nullable(),

                    TextInput::make('certification')->maxLength(255)->nullable(),

                    Textarea::make('bio')->rows(3)->nullable(),
                    Textarea::make('education')->rows(3)->nullable(),

                    TextInput::make('consultation_fee_online')->numeric()->nullable(),
                    TextInput::make('consultation_fee_inperson')->numeric()->nullable(),

                    TextInput::make('location')->maxLength(255)->nullable(),

                    Toggle::make('is_verified')->default(false),

                    TextInput::make('current_balance')->numeric()->default(0),
                ])
                ->columns(2),
        ];
    }
}
