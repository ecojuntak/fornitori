<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    public function reviewer() {
        return $this->hasOne('App\User', 'user_id');
    }
}
