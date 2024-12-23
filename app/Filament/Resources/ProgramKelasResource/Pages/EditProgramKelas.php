<?php

namespace App\Filament\Resources\ProgramKelasResource\Pages;

use App\Filament\Resources\ProgramKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgramKelas extends EditRecord
{
    protected static string $resource = ProgramKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
