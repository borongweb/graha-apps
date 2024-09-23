<?php
namespace App\Filament\Resources\ConsumerResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ConsumerResource;
use Illuminate\Routing\Router;


class ConsumerApiService extends ApiService
{
    protected static string | null $resource = ConsumerResource::class;

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
