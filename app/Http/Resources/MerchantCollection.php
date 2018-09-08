<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(type="object")
 */
class MerchantCollection extends ResourceCollection
{
    /**
     * @OA\Property(property="id",type="integer")
     * @OA\Property(property="name",type="string")
     *
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
