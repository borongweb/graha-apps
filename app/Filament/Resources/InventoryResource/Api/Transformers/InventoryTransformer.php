<?php
namespace App\Filament\Resources\InventoryResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryTransformer extends JsonResource
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
            'name' => $this->name,
            'information' => $this->information,
            'qty' => $this->qty,
            'price' => $this->price,
            'file_image' => $this->file_image,
            'file_payment' => $this->file_payment,
            'user_id' => $this->user_id,
            'consignee' => $this->consignee,
            'time' => $this->time,
        ];
    }
}