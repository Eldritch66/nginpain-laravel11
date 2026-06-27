<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PropertiRelationManager extends RelationManager
{
    protected static string $relationship = 'properti';

    protected static ?string $title = 'Properti Dimiliki';

    protected static ?string $recordTitleAttribute = 'nama_properti';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->role === 'pemilik';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_properti')
                    ->label('Nama Properti'),
                TextColumn::make('tipe')
                    ->badge(),
                TextColumn::make('kota'),
                TextColumn::make('harga_per_bulan')
                    ->label('Harga/bulan')
                    ->money('IDR'),
            ]);
    }
}
