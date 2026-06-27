<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SewaRelationManager extends RelationManager
{
    protected static string $relationship = 'sewa';

    protected static ?string $title = 'Riwayat Sewa';

    protected static ?string $recordTitleAttribute = 'id';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->role === 'penyewa';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('properti.nama_properti')
                    ->label('Properti'),
                TextColumn::make('tanggal_mulai')
                    ->date(),
                TextColumn::make('tanggal_selesai')
                    ->date(),
                TextColumn::make('total_harga')
                    ->money('IDR'),
                TextColumn::make('status_sewa')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'pending' => 'warning',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }
}
