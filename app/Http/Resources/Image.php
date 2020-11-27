<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Image extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'path' => Storage::url($this->name),
            'alt' => $this->alt,
            'extension' => $this->extension,
            'mime_type' => $this->mime_type,
            'width' => $this->width,
            'height' => $this->height,
            'user_id' => $this->user_id,
            'small' => "small-".$this->name,
            'uri' => $this->uri,
        ];
    }
}
