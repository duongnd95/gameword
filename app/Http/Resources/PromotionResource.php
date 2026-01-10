<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
	    $width = $height = 0;
	    $banner = null;
	    if(\Storage::exists($this->banner)){
		    $banner = asset(\Storage::url($this->banner));
		    list($width, $height) = getimagesize(public_path(\Storage::url($this->banner)));
	    }
        return [
        	'id' => $this->id,
	        'title' => $this->title,
			'banner' => [
				"src" => $banner,
				"width" => $width,
				"height" => $height,
			],
	        'position' => $this->positive,
	        // 'content' => $this->content
        ];
    }
}
