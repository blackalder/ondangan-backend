<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     // 'image_url' => $this->image ? URL::to($this->image) : null,
        //     'title' => $this->title,
        //     'male_name' => $this->male_name,
        //     'male_name' => $this->male_name,
        //     'male_name' => $this->male_name,
        //     'male_name' => $this->male_name,
        //     'male_name' => $this->male_name,
        //     'male_name' => $this->male_name,
        //     'male_name' => $this->male_name,
        //     // 'status' => !!$this->status,
        //     // 'description' => $this->description,
        //     // 'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
        //     // 'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
        //     // 'expire_date' => (new \DateTime($this->expire_date))->format('Y-m-d'),
        // ];
    }
}