<?php

namespace App\Filament\Resources\LaporanTrialClassResource\Pages;

use App\Filament\Resources\LaporanTrialClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanTrialClass extends EditRecord
{
    protected static string $resource = LaporanTrialClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
