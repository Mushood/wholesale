<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $prices = [];
        foreach ($this->prices as $price) {
            array_push($prices, [
                'price'     => $price->price,
                'quantity'  => $price->quantity,
                'currency'  => $price->currency->name,
                'price_usd' => $price->price,
            ]);
        }

        return [
            'title'         => $this->title,
            'subtitle'      => $this->subtitle,
            'introduction'  => $this->introduction,
            'body'          => $this->body,
            'views'         => $this->views,
            'shop'          => $this->shop()->exists() ? $this->shop->title : null,
            'category'      => $this->category()->exists() ? $this->category->title : null,
            'brand'         => $this->brand()->exists() ? $this->brand->title : null,
            'min_quantity'  => $this->min_quantity,
            'measure'       => $this->measure,
            'prices'        => $prices,
        ];
    }
}
