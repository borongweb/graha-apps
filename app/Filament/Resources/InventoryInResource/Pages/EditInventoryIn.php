<?php

namespace App\Filament\Resources\InventoryInResource\Pages;

use App\Filament\Resources\InventoryInResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryIn extends EditRecord
{
    protected static string $resource = InventoryInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}