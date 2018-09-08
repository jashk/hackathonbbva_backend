<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $muid
 * @property string $firts_name
 * @property string $last_name
 * @property string $phone
 * @property string $business_social_name
 * @property string $business_social_rfc
 * @property string $business_social_address
 * @property float $business_social_address_lat
 * @property float $business_social_address_lng
 * @property string $business_start
 * @property string $business_end
 * @property integer $approved
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Merchant extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Merchant';

    /**
     * @var array
     */
    protected $fillable = ['muid', 'firts_name', 'last_name', 'phone', 'business_social_name', 'business_social_rfc', 'business_social_address', 'business_social_address_lat', 'business_social_address_lng', 'business_start', 'business_end', 'approved', 'status', 'created_at', 'updated_at'];

}
