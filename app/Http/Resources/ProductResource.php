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
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'category'=>$this->category->name,
            'subcategory'=>$this->subcategory?$this->subcategory->name:null,
            'category_id'=>$this->category->id,
            'subcategory_id'=>$this->subcategory?$this->subcategory->id:null,
            //'image'=>$this->image?asset('images/' . $this->image):null
        ];
    }
}
