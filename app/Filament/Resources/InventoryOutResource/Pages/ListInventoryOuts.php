<?php

namespace App\Filament\Resources\InventoryOutResource\Pages;

use App\Filament\Resources\InventoryOutResource;
use App\Models\Inventory;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\EditAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use Filament\Forms\Components\TextInput;

class ListInventoryOuts extends ListRecords
{
    protected static string $resource = InventoryOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->icon('heroicon-o-arrow-down-tray')
                ->label('Laporan Excel')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(fn($resource) => 'Laporan Barang Keluar' . '-' . date('Y-m-d'))
                        ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                ]),
                
            Actions\CreateAction::make()->label('Tambah Barang Keluar')->icon('heroicon-o-plus'),
        ];
    }
}