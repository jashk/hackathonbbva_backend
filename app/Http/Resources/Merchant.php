<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class Merchant extends JsonResource
{
    /**
     * @OA\Property(property="id",type="integer")
     * @OA\Property(property="name",type="string")
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->firts_name,
        ];
    }
}
