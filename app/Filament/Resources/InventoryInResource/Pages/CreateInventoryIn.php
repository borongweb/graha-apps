<?php

namespace App\Filament\Resources\InventoryInResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\InventoryInResource;

class CreateInventoryIn extends CreateRecord
{
    protected static string $resource = InventoryInResource::class;

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