<?php
namespace App\Filament\Resources\InventoryOutResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\InventoryOutResource;
use Illuminate\Routing\Router;


class InventoryOutApiService extends ApiService
{
    protected static string | null $resource = InventoryOutResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
