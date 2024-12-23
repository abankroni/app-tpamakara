<?php

namespace App\Filament\Resources\DetailOrangTuaResource\Pages;

use App\Filament\Resources\DetailOrangTuaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailOrangTuas extends ListRecords
{
    protected static string $resource = DetailOrangTuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
