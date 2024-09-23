<?php

namespace App\Filament\Resources\ConsumerResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ConsumerResource;
use Filament\Notifications\Notification;

class EditConsumer extends EditRecord
{
    protected static string $resource = ConsumerResource::class;

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