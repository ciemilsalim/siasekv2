<?php

namespace App\Filament\Resources\AbsenMuridResource\Pages;

use App\Filament\Resources\AbsenMuridResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAbsenMurids extends ManageRecords
{
    protected static string $resource = AbsenMuridResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
