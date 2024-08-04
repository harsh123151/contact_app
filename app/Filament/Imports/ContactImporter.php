<?php
namespace App\Filament\Imports;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ContactImporter extends Importer
{
    protected static ?string $model = Contact::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('first_name')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('last_name')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('contact_no')
                ->requiredMapping()
                ->rules(['required'])
        ];
    }

    public function resolveRecord(): ?Contact
    {
        try {
            $contact = Contact::updateOrCreate(
                ['contact_no' => $this->data['contact_no']],
                [
                    'first_name' => $this->data['first_name'],
                    'last_name' => $this->data['last_name'],
                ]
            );

            return $contact;
        } catch (\Exception $e) {
            Log::error('Error resolving contact record: ' . $e->getMessage(), [
                'data' => $this->data,
                'exception' => $e,
            ]);
            return null;
        }
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your contact import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
