<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false; //non-incrementing or non-numeric primary

    protected $guarded = []; // state non-fillable fields, if empty means all fields are fillable

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    // protected $fillable = [
    //     'id'
    //     'name',
    //     'price',
    //     'brand',
    //     'category',
    //     'description',
    //     'image',
    //     'is_popular',
    //     'stock',
    //     'sales',
    //     'seller_id',
    // ];
}
