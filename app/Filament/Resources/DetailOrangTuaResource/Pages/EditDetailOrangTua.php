<?php

namespace App\Filament\Resources\DetailOrangTuaResource\Pages;

use App\Filament\Resources\DetailOrangTuaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailOrangTua extends EditRecord
{
    protected static string $resource = DetailOrangTuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
