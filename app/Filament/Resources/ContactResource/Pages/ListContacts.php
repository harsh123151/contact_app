<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Exports\ContactExporter;
class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ExportAction::make()
            //     ->exporter(ContactExporter::class)
            //     ->formats([
            //         \Filament\Actions\Exports\Enums\ExportFormat::Csv,
            //     ])
            //     ->fileName('contacts_export.csv')
            Actions\CreateAction::make()
        ];
    }
}
