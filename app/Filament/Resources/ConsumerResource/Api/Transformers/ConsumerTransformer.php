<?php
namespace App\Filament\Resources\ConsumerResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsumerTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
