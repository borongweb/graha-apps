<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Api\Transformers\InventoryTransformer;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Inventory;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Livewire\Attributes\Layout;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\InventoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Hydrat\TableLayoutToggle\Facades\TableLayoutToggle;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use App\Filament\Resources\InventoryResource\RelationManagers;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryResource extends Resource
{

    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Gudang';

    protected static ?string $navigationLabel = 'Data Barang';

    public static function getPluralLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            return "Daftar Barang";
        } else
            return "Daftar Barang";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('name')
                //     ->label('Nama Barang')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\FileUpload::make('file_image')
                //     ->label('Unggah Foto Barang (maks 500 KB.)')
                //     ->required()
                //     ->maxSize(1024)
                //     ->imageEditor()
                //     ->directory('data-barang/foto-barang')
                //     ->image(),
                // Forms\Components\FileUpload::make('file_payment')
                //     ->label('Unggah Bukti Pembayaran (maks. 500 KB.)')
                //     ->maxSize(1024)
                //     ->imageEditor()
                //     ->directory('data-barang/bukti-pembayaran')
                //     ->required(),
                // Forms\Components\TextInput::make('price')
                //     ->label('Total Harga')
                //     ->numeric()
                //     ->prefix('Rp. ')
                //     ->required()
                //     ->mask(RawJs::make('$money($input)'))
                //     ->stripCharacters(',')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('qty_in')
                //     ->numeric()
                //     ->inputMode('decimal')
                //     ->label('Jumlah Barang (Contoh: 2 atau 2.5)')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\Textarea::make('information_in')
                //     ->label('Keterangan Barang Masuk')
                //     ->required(),
                // Forms\Components\TextInput::make('consignee')
                //     ->required()
                //     ->label('Sumber Barang')
                //     ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        $livewire = $table->getLivewire();
        return $table
            ->striped()
            ->columns(
                $livewire->isGridLayout()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->contentGrid(
                fn() => $livewire->isListLayout()
                    ? null
                    : [
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 3,
                    ]
            )
            ->filters([
                Filter::make('time')
                    ->form([
                        DatePicker::make('time_from')->label('Dari Tanggal'),
                        DatePicker::make('time_until')->label('Sampai Tanggal')->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['time_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('time', '>=', $date),
                            )
                            ->when(
                                $data['time_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('time', '<=', $date),
                            );
                    })
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getListTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Nama Barang')
                ->searchable(),
            Tables\Columns\TextColumn::make('price')
                ->money('IDR', locale: 'id')
                ->label('Total Harga')
                ->searchable(),
            Tables\Columns\TextColumn::make('qty_total')
                ->label('Jumlah Barang')
                ->searchable(),
            Tables\Columns\TextColumn::make('user.name')
                ->label('Nama Petugas'),
        ];
    }
    public static function getGridTableColumns(): array
    {
        return [
            // Make sure to stack your columns together
            Tables\Columns\Layout\Stack::make([
                Tables\Columns\TextColumn::make('qty')
                    ->badge()
                    ->weight(FontWeight::Bold),
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->weight(FontWeight::Bold)
                        ->searchable(),
                    Tables\Columns\ImageColumn::make('file_image'),
                    Tables\Columns\TextColumn::make('user.name')
                        ->description(__('Nama Staff'), position: 'above')
                        ->weight(FontWeight::Bold)
                        ->searchable(),
                ]),
            ])->space(3)->extraAttributes([
                'class' => 'pb-2',
            ]),
        ];
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')->label('Nama Barang'),
                TextEntry::make('user.name')->label('Nama Staff'),
                TextEntry::make('price')->label('price'),
                TextEntry::make('qty')->label('Jumlah Barang'),
                TextEntry::make('consignee')->label('Sumber'),
                ImageEntry::make('file_image')->label('Foto Barang'),
                ImageEntry::make('file_payment')->label('Bukti Pembayaran'),
                TextEntry::make('information')->label('Keterangan')->columnSpan(2),
            ])->columns(3);
    }

    public static function getApiTransformer(){
        return InventoryTransformer::class;
    }

}