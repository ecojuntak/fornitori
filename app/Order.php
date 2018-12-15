<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'quantity'
    ];

    public function detail() {
        return $this->hasOne('App\OrderDetail');
    }

    public function products() {
        return $this->belongsToMany('App\Product', 'order_details', 'order_id', 'product_id');
    }

    public function customer() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function shipping() {
        return $this->hasOne('App\Shipping');
    }

    public function payment() {
        return $this->hasOne('App\Payment');
    }
}
