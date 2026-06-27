<?php

namespace App\Filament\Resources\TiketBantuanResource\Pages;

use App\Filament\Resources\TiketBantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTiketBantuan extends ViewRecord
{
    protected static string $resource = TiketBantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
