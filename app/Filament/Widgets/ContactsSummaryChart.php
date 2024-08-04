<?php
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ContactsSummaryChart extends ChartWidget
{
    protected static ?string $heading = 'Contacts and Groups Summary';

    protected function getData(): array
    {

        $totalGroups = \App\Models\Group::count();
        $totalContacts = \App\Models\Contact::count();

        return [
            'datasets' => [
                [
                    'label' => 'Counts',
                    'data' => [$totalGroups, $totalContacts],
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)', 
                        'rgba(255, 99, 132, 0.2)', 
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)', 
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Total Groups', 'Total Contacts'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
