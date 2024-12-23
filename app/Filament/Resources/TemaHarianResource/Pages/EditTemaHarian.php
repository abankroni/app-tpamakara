<?php

namespace App\Filament\Resources\TemaHarianResource\Pages;

use App\Filament\Resources\TemaHarianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTemaHarian extends EditRecord
{
    protected static string $resource = TemaHarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
