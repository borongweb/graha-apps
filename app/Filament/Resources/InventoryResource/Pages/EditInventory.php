<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\InventoryResource;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;

class EditInventory extends EditRecord
{   
    use HasToggleableTable;

    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}