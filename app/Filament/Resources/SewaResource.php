<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SewaResource\Pages;
use App\Models\Sewa;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SewaResource extends Resource
{
    protected static ?string $model = Sewa::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penyewa.name')
                    ->label('Penyewa')
                    ->searchable(),
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
            ])
            ->filters([
                SelectFilter::make('status_sewa')
                    ->options([
                        'aktif' => 'Aktif',
                        'pending' => 'Pending',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('penyewa_id')
                    ->relationship('penyewa', 'name')
                    ->label('Penyewa'),
                Select::make('properti_id')
                    ->relationship('properti', 'nama_properti')
                    ->label('Properti'),
                DatePicker::make('tanggal_mulai'),
                DatePicker::make('tanggal_selesai'),
                TextInput::make('durasi_bulan')
                    ->label('Durasi (bulan)')
                    ->numeric(),
                TextInput::make('total_harga')
                    ->numeric(),
                TextInput::make('biaya_layanan')
                    ->numeric(),
                Select::make('status_sewa')
                    ->options([
                        'aktif' => 'Aktif',
                        'pending' => 'Pending',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('penyewa.name')
                    ->label('Penyewa'),
                TextEntry::make('properti.nama_properti')
                    ->label('Properti'),
                TextEntry::make('tanggal_mulai')
                    ->date(),
                TextEntry::make('tanggal_selesai')
                    ->date(),
                TextEntry::make('durasi_bulan')
                    ->label('Durasi'),
                TextEntry::make('total_harga')
                    ->money('IDR'),
                TextEntry::make('biaya_layanan')
                    ->money('IDR'),
                TextEntry::make('status_sewa')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'pending' => 'warning',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSewas::route('/'),
            'view' => Pages\ViewSewa::route('/{record}'),
        ];
    }
}
