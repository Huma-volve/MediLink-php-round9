<?php

namespace App\Filament\Resources\Appointments\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Time')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('consultation_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('doctor.user.name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('doctor.specialization.name')
                    ->label('Specialization')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('patient.user.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reason_for_visit')
                    ->label('Reason')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'upcoming' => 'Upcoming',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('consultation_type')
                    ->label('Type')
                    ->options([
                        'in_person' => 'In person',
                        'online' => 'Online',
                    ]),

                Tables\Filters\Filter::make('today')
                    ->query(fn($query) => $query->whereDate('appointment_date', now()->toDateString()))
                    ->label('Today'),

                Tables\Filters\Filter::make('this_week')
                    ->query(fn($query) => $query->whereBetween('appointment_date', [
                        now()->startOfWeek()->toDateString(),
                        now()->endOfWeek()->toDateString(),
                    ]))
                    ->label('This week'),
            ])
            ->recordActions([])      // no edit
            ->toolbarActions([]);    // no create / bulk actions
    }
}
