<?php

namespace App\Filament\Resources\InventoryInResource\Pages;

use App\Filament\Resources\InventoryInResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListInventoryIns extends ListRecords
{
    protected static string $resource = InventoryInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->icon('heroicon-o-arrow-down-tray')
                ->label('Laporan Excel')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(fn($resource) => 'Laporan Barang Masuk' . '-' . date('Y-m-d'))
                        ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                ]),
            Actions\CreateAction::make()->label('Tambah Barang')->icon('heroicon-o-plus'),
        ];
    }
}