<?php

namespace App\Filament\Resources\ConsumerResource\Pages;

use Filament\Actions;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ConsumerResource;

class ListConsumers extends ListRecords
{
    protected static string $resource = ConsumerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
}