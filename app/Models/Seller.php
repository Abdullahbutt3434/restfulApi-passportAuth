<?php

namespace App\Models;

use App\Scopes\SellerScope;
use App\Transformers\BuyerTransformer;
use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    use HasFactory;
    protected $table = 'users';
    public $transformer = SellerTransformer::class;


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SellerScope());
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }
}
