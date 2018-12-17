<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    public function merchant() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function carts() {
        return $this->belongsToMany('App\Cart', 'cart_details',
            'product_id', 'cart_id')->withPivot('quantity');;
    }

    public function cartDetail() {
        return $this->belongsTo('App\CartDetail');
    }

    public function reviews() {
        return $this->hasMany('App\ProductReview');
    }

    protected $fillable = ['name', 'price', 'stock', 'description', 'category', 'specification', 'images', 'color'];
    protected $dates = ['deleted_at'];
}
	