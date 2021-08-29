<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderArticle extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_order',
        'id_article',
        "quantity",
        "unit_price",
        "total_price"
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function article(){
        return $this->hasOne(Article::class,'id','article_id');
    }
}
