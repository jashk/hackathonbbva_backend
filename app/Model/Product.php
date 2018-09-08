<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $merchant_id
 * @property string $name
 * @property string $imagen
 * @property float $price
 * @property Merchant $merchant
 */
class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['merchant_id', 'name', 'imagen', 'price'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }
}
