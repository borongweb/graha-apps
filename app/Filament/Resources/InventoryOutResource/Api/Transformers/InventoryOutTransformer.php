<?php
namespace App\Filament\Resources\InventoryOutResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryOutTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return $this->resource->toArray();

        return [
            'id' => $this->id,
            'time' => $this->time,
            'information' => $this->information,
            'qty' => $this->qty,
            'inventories_id' => $this->inventories_id,
            'user_id' => $this->user_id,
        ];
    }
}