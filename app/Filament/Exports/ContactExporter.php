<?php

namespace App\Filament\Exports;

use App\Models\Contact;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ContactExporter extends Exporter
{
    protected static ?string $model = Contact::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('first_name')->label('First Name'),
            ExportColumn::make('last_name')->label('Last Name'),
            ExportColumn::make('contact_no')->label('Contact Number'),
            ExportColumn::make('groups')
            ->label('Groups')
            ->formatStateUsing(fn(Contact $record) => implode(',', $record->groups->pluck('name')->toArray())),
        ];
    }

    public function getFormats(): array
    {
        return [
            \Filament\Actions\Exports\Enums\ExportFormat::Csv,
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your contact export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
