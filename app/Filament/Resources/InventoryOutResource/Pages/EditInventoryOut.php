<?php

namespace App\Filament\Resources\InventoryOutResource\Pages;

use App\Filament\Resources\InventoryOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;

class EditInventoryOut extends EditRecord
{
    protected static string $resource = InventoryOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}