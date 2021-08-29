<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "total_amount_without_discount",
        "total_amount_with_discount"
    ];
    public function orderArticles()
    {
        return $this->hasMany(OrderArticle::class);
    }
}
