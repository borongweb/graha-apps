<?php

namespace App\Filament\Resources\ConsumerResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ConsumerResource;

class CreateConsumer extends CreateRecord
{
    protected static string $resource = ConsumerResource::class;

    protected static ?string $title = 'Tambah Konsumen';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        if($data['file_payment'] != null) {
            $data['status'] = 'Booking';
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}