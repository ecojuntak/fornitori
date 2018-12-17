<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'quantity'
    ];

    public function products() {
        return $this->hasMany('App\Product');
    }
}
