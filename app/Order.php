<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'quantity'
    ];

    public function details() {
        return $this->hasMany('App\OrderDetail');
    }

    public function customer() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
