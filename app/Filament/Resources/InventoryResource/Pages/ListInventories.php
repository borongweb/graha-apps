<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\InventoryResource;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListInventories extends ListRecords
{
    use HasToggleableTable;
    
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            ExportAction::make()
            ->icon('heroicon-o-arrow-down-tray')
            ->label('Laporan Excel')
            ->exports([
                ExcelExport::make()
                    ->fromTable()
                    ->withFilename(fn($resource) => 'Laporan Barang' . '-' . date('Y-m-d'))
                    ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
            ]),
        ];
    }
}