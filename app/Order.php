<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'merchant_id', 'product_id'
    ];

    public function detail() {
        return $this->hasOne('App\OrderDetail');
    }

    public function products() {
        return $this->belongsToMany('App\Product', 'order_details', 'order_id', 'product_id');
    }

    public function customer() {
        return $this->belongsTo('App\User', 'customer_id');
    }

    public function merchant() {
        return $this->belongsTo('App\User', 'merchant_id');
    }

    public function shipping() {
        return $this->hasOne('App\Shipping');
    }

    public function payment() {
        return $this->hasOne('App\Payment');
    }
}
