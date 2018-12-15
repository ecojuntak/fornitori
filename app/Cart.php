<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'product_id', 'user_id'
    ];

    protected $dates = ['deleted_at'];

    public function products() {
        return $this->belongsToMany('App\Product', 'cart_details', 'cart_id', 'product_id');
    }

    public function details() {
        return $this->hasMany('App\CartDetail');
    }

    public function customer() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
