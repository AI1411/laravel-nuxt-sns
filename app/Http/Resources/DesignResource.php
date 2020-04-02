<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Psy\Util\Str;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'title' => $this->title,
            'slug' => $this->slug,
            'images' => $this->images,
            'description' => $this->dscription,
            'tag_list' => [
                'tags' => $this->tagArray,
                'normalized' => $this->tagArrayNormalized,
            ],
            'created_at_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],
            'update_at_dates' => [
                'update_at_human' => $this->updated_at->diffForHumans(),
                'update_at' => $this->updated_at
            ],
        ];
    }
}
