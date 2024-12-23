<?php

namespace App\Filament\Resources\LaporanTrialClassResource\Pages;

use App\Filament\Resources\LaporanTrialClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanTrialClasses extends ListRecords
{
    protected static string $resource = LaporanTrialClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
