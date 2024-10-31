<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['name','img','description'];
    ////relationships
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    public function prices(){
        return $this->hasMany(Price::class);
    }
    protected function Name(){
        return Attribute::make(
            get: fn ($value)=>ucfirst($value),
            set: fn ($value)=>ucfirst($value),
        );

    }
}
