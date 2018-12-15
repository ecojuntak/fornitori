<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartDetail extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'quantity'];

    public function product() {
        return $this->belongsTo('App\Product');
    }

    public function cart() {
        return $this->hasOne('App\Cart');
    }
}
