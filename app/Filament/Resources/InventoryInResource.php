<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Inventory;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Pages\ListInventoryIns;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InventoryInResource\Pages;
use App\Filament\Resources\InventoryInResource\RelationManagers;

class InventoryInResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Gudang';

    protected static ?string $navigationLabel = 'Data Barang Masuk';

    protected static ?int $navigationSort = 2;

    public static function getPluralLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            return "Daftar Barang Masuk";
        } else
            return "Daftar Barang Masuk";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Barang')
                    ->autocomplete('off')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->maxLength(255),
                Forms\Components\TextInput::make('qty')
                    ->numeric()
                    ->label('Jumlah Barang (Contoh: 2 atau 2.5)')
                    ->required(),
                Forms\Components\DatePicker::make('time')
                    ->date()
                    ->label('Tanggal Masuk')
                    ->required()
                    ->default(now()),
                Forms\Components\Textarea::make('information')
                    ->label('Keterangan Barang Masuk')
                    ->required(),
                Forms\Components\TextInput::make('consignee')
                    ->required()
                    ->label('Sumber Barang')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('file_image')
                    ->label('Unggah Foto Barang (maks 500 KB.)')
                    ->required()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->directory('data-barang/foto-barang')
                    ->image(),
                Forms\Components\FileUpload::make('file_payment')
                    ->label('Unggah Bukti Pembayaran (maks. 500 KB.)')
                    ->maxSize(1024)
                    ->imageEditor()
                    ->directory('data-barang/bukti-pembayaran')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->label('Total Harga')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Jumlah Barang Masuk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Ditambahkan Pada')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Petugas'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->modalHeading(fn(Inventory $record) => $record->name),
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn(Inventory $record) => $record->delete())
                    ->action(function (Inventory $record) {
                        if ($record->file_image != null && Storage::disk('public')->exists($record->file_image)) {
                            Storage::disk('public')->delete($record->file_image);
                        }
                        if ($record->file_payment != null && Storage::disk('public')->exists($record->file_payment)) {
                            Storage::disk('public')->delete($record->file_payment);
                        }
                        $record->delete();
                    })
                    ->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->action(function (Inventory $record) {
                        $record->each(function ($record) {
                            if ($record->file_image != null && Storage::disk('public')->exists($record->file_image)) {
                                Storage::disk('public')->delete($record->file_image);
                            }
                            if ($record->file_payment != null && Storage::disk('public')->exists($record->file_payment)) {
                                Storage::disk('public')->delete($record->file_payment);
                            }
                            $record->delete();
                        });
                    })
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')->label('Nama Barang'),
                TextEntry::make('user.name')->label('Nama Staff'),
                TextEntry::make('price')->label('price')->numeric()->money('IDR', locale: 'id'),
                TextEntry::make('qty')->label('Jumlah Barang'),
                TextEntry::make('consignee')->label('Sumber'),
                ImageEntry::make('file_image')->label('Foto Barang'),
                ImageEntry::make('file_payment')->label('Bukti Pembayaran'),
                TextEntry::make('information')->label('Keterangan')->columnSpan(2),
            ])->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryIns::route('/'),
            'create' => Pages\CreateInventoryIn::route('/create'),
            'edit' => Pages\EditInventoryIn::route('/{record}/edit'),
        ];
    }
}