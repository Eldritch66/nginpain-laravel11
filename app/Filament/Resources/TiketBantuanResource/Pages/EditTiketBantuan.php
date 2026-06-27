<?php

namespace App\Filament\Resources\TiketBantuanResource\Pages;

use App\Filament\Resources\TiketBantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditTiketBantuan extends EditRecord
{
    protected static string $resource = TiketBantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['status'] === 'selesai' || $data['balasan_admin']) {
            $data['dijawab_pada'] = now('Asia/Jakarta');
            $data['dijawab_oleh'] ??= Auth::guard('admin')->id();
        }

        return $data;
    }
}
