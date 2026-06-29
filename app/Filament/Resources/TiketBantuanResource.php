<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiketBantuanResource\Pages;
use App\Models\TiketBantuan;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TiketBantuanResource extends Resource
{
    protected static ?string $model = TiketBantuan::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan';

    protected static ?string $recordTitleAttribute = 'judul';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_tiket')
                    ->label('No. Tiket')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Pengirim')
                    ->searchable(),
                TextColumn::make('judul')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'teknis' => 'gray',
                        'pembayaran' => 'success',
                        'properti' => 'warning',
                        'akun' => 'info',
                        'lainnya' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        'ditutup' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
                TextColumn::make('penjawab.nama')
                    ->label('Dijawab Oleh')
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->options([
                        'teknis' => 'Teknis',
                        'pembayaran' => 'Pembayaran',
                        'properti' => 'Properti',
                        'akun' => 'Akun',
                        'lainnya' => 'Lainnya',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditutup' => 'Ditutup',
                    ]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options([
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditutup' => 'Ditutup',
                    ])
                    ->required(),
                Textarea::make('balasan_admin')
                    ->label('Balasan Admin')
                    ->rows(5),
                Section::make('Info Tiket')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Pengirim')
                            ->disabled(),
                        TextInput::make('judul')
                            ->disabled(),
                        Textarea::make('pesan')
                            ->disabled()
                            ->rows(4),
                        Select::make('kategori')
                            ->options([
                                'teknis' => 'Teknis',
                                'pembayaran' => 'Pembayaran',
                                'properti' => 'Properti',
                                'akun' => 'Akun',
                                'lainnya' => 'Lainnya',
                            ])
                            ->disabled(),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('no_tiket')
                    ->label('No. Tiket'),
                TextEntry::make('judul')
                    ->size('lg')
                    ->weight('bold'),
                TextEntry::make('user.name')
                    ->label('Pengirim'),
                TextEntry::make('user.email')
                    ->label('Email Pengirim'),
                TextEntry::make('kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'teknis' => 'gray',
                        'pembayaran' => 'success',
                        'properti' => 'warning',
                        'akun' => 'info',
                        'lainnya' => 'danger',
                        default => 'gray',
                    }),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        'ditutup' => 'gray',
                        default => 'gray',
                    }),
                TextEntry::make('pesan')
                    ->label('Pesan')
                    ->markdown()
                    ->columnSpanFull(),
                TextEntry::make('balasan_admin')
                    ->label('Balasan Admin')
                    ->markdown()
                    ->columnSpanFull()
                    ->placeholder('Belum ada balasan'),
                TextEntry::make('penjawab.nama')
                    ->label('Dijawab Oleh'),
                TextEntry::make('dijawab_pada')
                    ->label('Dijawab Pada')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTiketBantuans::route('/'),
            'view' => Pages\ViewTiketBantuan::route('/{record}'),
            'edit' => Pages\EditTiketBantuan::route('/{record}/edit'),
        ];
    }
}
