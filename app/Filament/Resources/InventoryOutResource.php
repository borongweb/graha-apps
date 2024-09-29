<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Inventory;
use Filament\Tables\Table;
use App\Models\InventoryOut;
use Filament\Resources\Resource;
// use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InventoryOutResource\Pages;
use App\Filament\Resources\InventoryOutResource\RelationManagers;
use App\Filament\Resources\InventoryOutResource\Api\Transformers\InventoryOutTransformer;

class InventoryOutResource extends Resource
{
    protected static ?string $model = InventoryOut::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Gudang';

    protected static ?string $navigationLabel = 'Data Barang Keluar';

    protected static ?int $navigationSort = 3;

    public static function getPluralLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            return "Daftar Barang Keluar";
        } else
            return "Daftar Barang Keluar";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('inventories_id')
                    ->label('Nama Barang')
                    ->required()
                    ->options(Inventory::query()->pluck('name', 'id')),
                DatePicker::make('time')
                    ->date()
                    ->default(now())
                    ->required()
                    ->label('Tanggal Keluar'),
                TextInput::make('qty')
                    ->numeric()
                    ->label('Jumlah Barang Keluar')
                    ->required(),
                Textarea::make('information')
                    ->label('Keterangan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inventories.name')
                    ->label('Nama Barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Jumlah Barang Keluar')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('information')
                    ->label('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('time')
                    ->date()
                    ->label('Tanggal Keluar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Petugas')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListInventoryOuts::route('/'),
            'create' => Pages\CreateInventoryOut::route('/create'),
            // 'edit' => Pages\EditInventoryOut::route('/{record}/edit'),
        ];
    }

    public static function getApiTransformer(){
        return InventoryOutTransformer::class;
    } 
}