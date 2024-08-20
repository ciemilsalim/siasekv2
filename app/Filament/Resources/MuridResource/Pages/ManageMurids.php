<?php

namespace App\Filament\Resources\MuridResource\Pages;

use App\Filament\Resources\MuridResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMurids extends ManageRecords
{
    protected static string $resource = MuridResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
