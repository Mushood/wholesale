<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $items = [];
        foreach ($this->items as $item) {
            array_push($items, [
                'product'   => $item->product->title,
                'quantity'  => $item->quantity,
            ]);
        }

        return [
            'total'  => $this->getTotal(),
            'items'  => $items
        ];
    }
}
