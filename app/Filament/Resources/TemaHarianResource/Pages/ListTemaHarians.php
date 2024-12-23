<?php

namespace App\Filament\Resources\TemaHarianResource\Pages;

use App\Filament\Resources\TemaHarianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTemaHarians extends ListRecords
{
    protected static string $resource = TemaHarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
