<?php

namespace App\Filament\Resources\PendaftaranTrialClassResource\Pages;

use App\Filament\Resources\PendaftaranTrialClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendaftaranTrialClass extends EditRecord
{
    protected static string $resource = PendaftaranTrialClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
