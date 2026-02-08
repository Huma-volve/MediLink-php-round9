<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestWedget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = [
        'sm' => 12,
        'md' => 12,
    ];

    protected function getStats(): array
    {
        return [
            Stat::make('New Users' , User::count())
                ->description('New Users That Have Joined')
                ->descriptionIcon('heroicon-o-users' , IconPosition::Before)
                ->chart([1 , 3 , 5 , 10 , 20 , 40])
                ->color(Color::Green),
            Stat::make('New Doctors' , Doctor::count())
                ->description('New Doctors That Have Joined')
                ->descriptionIcon('heroicon-o-users' , IconPosition::Before)
                ->chart([1 , 3 , 5 , 10 , 20 , 40])
                ->color(Color::Red),
            Stat::make('New Patients' , Patient::count())
                ->description('New Patients That Have Joined')
                ->descriptionIcon('heroicon-o-users' , IconPosition::Before)
                ->chart([1 , 3 , 5 , 10 , 20 , 40])
                ->color(Color::Blue),
        ];
    }
}
