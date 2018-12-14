<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'product_id', 'quantity', 'user_id'
    ];

    protected $dates = ['deleted_at'];

    public function product() {
        return $this->belongsTo('App\Product');
    }

    public function customer() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
