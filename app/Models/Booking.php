<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'courier_integration_id',
        'consignment_no',
        'customer_name',
        'customer_phone',
        'customer_address',
        'destination_city',
        'weight',
        'cod_amount',
        'delivery_charges',
        'status',
    ];

    /**
     * Relationship with User (Shipper)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Courier Integration
     */
    public function courier_integration()
    {
        return $this->belongsTo(CourierIntegration::class, 'courier_integration_id');
    }
}