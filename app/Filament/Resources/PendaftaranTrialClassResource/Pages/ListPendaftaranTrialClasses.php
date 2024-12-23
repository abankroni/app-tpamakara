<?php

namespace App\Filament\Resources\PendaftaranTrialClassResource\Pages;

use App\Filament\Resources\PendaftaranTrialClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPendaftaranTrialClasses extends ListRecords
{
    protected static string $resource = PendaftaranTrialClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
