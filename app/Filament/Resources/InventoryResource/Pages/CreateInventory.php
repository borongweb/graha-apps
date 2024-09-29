<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\InventoryResource;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;

class CreateInventory extends CreateRecord
{   
    use HasToggleableTable;

    protected static string $resource = InventoryResource::class;
    
    protected static ?string $title = 'Tambah Barang';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}