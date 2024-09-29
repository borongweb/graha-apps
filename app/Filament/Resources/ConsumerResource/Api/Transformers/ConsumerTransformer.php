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
        // return $this->resource->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_marketing' => $this->name_marketing,
            'no_telp' => $this->no_telp,
            'status' => $this->status,
            'file_payment' => $this->file_payment,
            'file_id' => $this->file_id,
            'kavling' => $this->kavling,
            'register_on' => $this->register_on,
            'information' => $this->information,
            'user_id' => $this->user_id,
        ];
    }
}