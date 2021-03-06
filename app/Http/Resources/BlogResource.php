<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title'         => $this->title,
            'subtitle'      => $this->subtitle,
            'introduction'  => $this->introduction,
            'body'          => $this->body,
            'views'         => $this->views,
            'author'        => $this->author(),
            'category'      => $this->category != null ? $this->category->title : null,
            'slug'          => $this->slug,
        ];
    }
}
