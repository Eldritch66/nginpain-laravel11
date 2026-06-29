<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static string|\UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?string $recordTitleAttribute = 'kode_bayar';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_bayar')
                    ->label('Kode Bayar')
                    ->searchable(),
                TextColumn::make('sewa.kode_booking')
                    ->label('Kode Booking')
                    ->searchable(),
                TextColumn::make('sewa.penyewa.name')
                    ->label('Penyewa')
                    ->searchable(),
                TextColumn::make('jumlah')
                    ->money('IDR'),
                TextColumn::make('metode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'QRIS' => 'success',
                        'Transfer BCA' => 'info',
                        'PayPal' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'menunggu' => 'warning',
                        'ditolak' => 'danger',
                        'kadaluarsa' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('dibayar_pada')
                    ->label('Dibayar')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('metode')
                    ->options([
                        'QRIS' => 'QRIS',
                        'Transfer BCA' => 'Transfer BCA',
                        'PayPal' => 'PayPal',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'lunas' => 'Lunas',
                        'menunggu' => 'Menunggu',
                        'ditolak' => 'Ditolak',
                        'kadaluarsa' => 'Kadaluarsa',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sewa_id')
                    ->relationship('sewa', 'id')
                    ->label('Sewa')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Sewa #{$record->id} ({$record->penyewa->name})"),
                TextInput::make('jumlah')
                    ->numeric(),
                Select::make('metode')
                    ->options([
                        'QRIS' => 'QRIS',
                        'Transfer BCA' => 'Transfer BCA',
                        'PayPal' => 'PayPal',
                    ]),
                Select::make('status')
                    ->options([
                        'lunas' => 'Lunas',
                        'menunggu' => 'Menunggu',
                        'ditolak' => 'Ditolak',
                        'kadaluarsa' => 'Kadaluarsa',
                    ]),
                TextInput::make('periode_bulan')
                    ->label('Periode (bulan)')
                    ->numeric(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_bayar')
                    ->label('Kode Bayar'),
                TextEntry::make('sewa.kode_booking')
                    ->label('Kode Booking'),
                TextEntry::make('sewa.penyewa.name')
                    ->label('Penyewa'),
                TextEntry::make('jumlah')
                    ->money('IDR'),
                TextEntry::make('metode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'QRIS' => 'success',
                        'Transfer BCA' => 'info',
                        'PayPal' => 'warning',
                        default => 'gray',
                    }),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'menunggu' => 'warning',
                        'ditolak' => 'danger',
                        'kadaluarsa' => 'gray',
                        default => 'gray',
                    }),
                TextEntry::make('periode_bulan')
                    ->label('Periode (bulan)'),
                TextEntry::make('dibayar_pada')
                    ->label('Dibayar')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'view' => Pages\ViewPembayaran::route('/{record}'),
        ];
    }
}
