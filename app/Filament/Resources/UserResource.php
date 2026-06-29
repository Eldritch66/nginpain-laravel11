<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\PropertiRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\SewaRelationManager;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $pluralLabel = 'Pengguna';

    protected static ?string $recordTitleAttribute = 'name';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('no_hp')
                    ->label('No. HP'),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pemilik' => 'success',
                        'penyewa' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Daftar')
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'pemilik' => 'Pemilik',
                        'penyewa' => 'Penyewa',
                        'new' => 'Baru',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->disabled(fn (User $record) => $record->hasActiveSewa() || $record->hasPropertiWithActiveSewa())
                    ->tooltip(fn (User $record) => $record->hasActiveSewa()
                        ? 'User masih memiliki sewa aktif'
                        : ($record->hasPropertiWithActiveSewa()
                            ? 'Properti user masih ada yang disewa'
                            : ''))
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Pengguna')
                    ->modalDescription(fn (User $record) => $record->properti()->exists()
                        ? 'Pengguna ini memiliki properti dengan riwayat sewa. Properti akan dihapus permanen dan riwayat sewa penyewa akan kehilangan referensi properti. Lanjutkan?'
                        : 'Yakin ingin menghapus pengguna ini?')
                    ->action(fn (User $record) => $record->forceDelete()),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                TextInput::make('email'),
                TextInput::make('no_hp')
                    ->label('No. HP'),
                Select::make('role')
                    ->options([
                        'pemilik' => 'Pemilik',
                        'penyewa' => 'Penyewa',
                        'new' => 'Baru',
                    ])
                    ->disabled(fn (?User $record) => $record && ($record->hasActiveSewa() || $record->hasProperti()))
                    ->hint(fn (?User $record) => $record && ($record->hasActiveSewa() || $record->hasProperti()) ? 'Role tidak bisa diubah karena user masih memiliki sewa aktif atau properti' : ''),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('no_hp')
                    ->label('No. HP'),
                TextEntry::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pemilik' => 'success',
                        'penyewa' => 'info',
                        default => 'gray',
                    }),
                TextEntry::make('created_at')
                    ->label('Daftar')
                    ->date(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PropertiRelationManager::class,
            SewaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
