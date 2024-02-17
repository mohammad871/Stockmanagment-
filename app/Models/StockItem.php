<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $table = "stock_item";
    protected $fillable = ["stock_id", "item_id", "quantity", 'hijri_date'];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    use HasFactory;
}
