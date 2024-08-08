<?php

namespace App\Filament\Resources;

use App\Filament\Components\CustomMultiSelect;
use App\Filament\Components\GroupMultiSelect;
use App\Filament\Exports\ContactExporter;
use App\Filament\Imports\ContactImporter;
use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Actions\CreateAction;
// use Filament\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;
use App\Livewire\Dropdown;
use App\Models\Group;
use Filament\Forms\Components\Select;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('first_name')
                ->required()
                ->string()->alpha(),

            TextInput::make('last_name')
                ->required()
                ->string()->alpha(), 

            TextInput::make('contact_no')
                ->required()
                ->tel(), 
                Select::make('groups')
                ->multiple()
                ->relationship('groups', 'name')
                ->preload()
                ->optionsLimit(5)
                ->createOptionForm([
                    TextInput::make('name')
                        ->required()
                        ->regex('/^[a-zA-Z\s]+$/'),
                ])
                ->createOptionUsing(function (array $data) {
                    $validatedData = validator($data, [
                            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
                        ])->validate();
                    $group = Group::firstOrCreate(['name' => $validatedData['name']]);
                    return $group->id;
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name'),
                TextColumn::make('last_name'),
                TextColumn::make('contact_no'),
                TextColumn::make('groups.name')->label('Groups'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(ContactExporter::class),
                ImportAction::make()
                    ->importer(ContactImporter::class),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
