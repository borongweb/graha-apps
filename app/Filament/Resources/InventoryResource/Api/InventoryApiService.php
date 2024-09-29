<?php
namespace App\Filament\Resources\InventoryResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\InventoryResource;
use Illuminate\Routing\Router;


class InventoryApiService extends ApiService
{
    protected static string | null $resource = InventoryResource::class;

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
