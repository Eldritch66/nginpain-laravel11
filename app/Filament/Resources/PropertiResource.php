<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertiResource\Pages;
use App\Models\Properti;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PropertiResource extends Resource
{
    protected static ?string $model = Properti::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string|\UnitEnum|null $navigationGroup = 'Properti';

    protected static ?string $recordTitleAttribute = 'nama_properti';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto.url')
                    ->label('Foto')
                    ->rounded()
                    ->size(40),
                TextColumn::make('nama_properti')
                    ->searchable(),
                TextColumn::make('pemilik.name')
                    ->label('Pemilik'),
                TextColumn::make('tipe')
                    ->badge(),
                TextColumn::make('kota'),
                TextColumn::make('harga_per_bulan')
                    ->label('Harga/bulan')
                    ->money('IDR'),
            ])
            ->filters([
                SelectFilter::make('tipe')
                    ->options([
                        'kost' => 'Kost',
                        'kontrakan' => 'Kontrakan',
                    ]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_properti'),
                Select::make('pemilik_id')
                    ->relationship('pemilik', 'name')
                    ->label('Pemilik'),
                Select::make('tipe')
                    ->options([
                        'kost' => 'Kost',
                        'kontrakan' => 'Kontrakan',
                    ]),
                TextInput::make('kota'),
                Textarea::make('alamat'),
                TextInput::make('harga_per_bulan')
                    ->label('Harga per Bulan')
                    ->numeric(),
                TextInput::make('harga_per_dua_bulan')
                    ->label('Harga per Dua Bulan')
                    ->numeric(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('foto.url')
                    ->label('Foto')
                    ->imageSize(300)
                    ->stacked()
                    ->circular(),
                TextEntry::make('nama_properti'),
                TextEntry::make('pemilik.name')
                    ->label('Pemilik'),
                TextEntry::make('tipe')
                    ->badge(),
                TextEntry::make('kota'),
                TextEntry::make('alamat'),
                TextEntry::make('harga_per_bulan')
                    ->label('Harga per Bulan')
                    ->money('IDR'),
                TextEntry::make('harga_per_dua_bulan')
                    ->label('Harga per Dua Bulan')
                    ->money('IDR'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropertis::route('/'),
            'view' => Pages\ViewProperti::route('/{record}'),
        ];
    }
}
