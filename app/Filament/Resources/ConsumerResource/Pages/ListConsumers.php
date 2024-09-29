<?php

namespace App\Filament\Resources\ConsumerResource\Pages;

use Filament\Actions;
use App\Models\Inventory;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Exports\ConsumerExporter;
use Filament\Actions\Exports\Models\Export;
use App\Filament\Resources\ConsumerResource;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Actions\Exports\Enums\ExportFormat;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use Illuminate\Database\Eloquent\Builder;

class ListConsumers extends ListRecords
{

    use HasToggleableTable;

    protected static string $resource = ConsumerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->icon('heroicon-o-arrow-down-tray')
                ->label('Laporan Excel')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(fn($resource) => $resource::getModelLabel() . '-' . date('Y-m-d'))
                        ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                        ->withColumns([
                            Column::make('status'),
                        ])
                ]),
            Actions\CreateAction::make()->label('Tambah Konsumen')->icon('heroicon-o-plus'),
        ];
    }
}