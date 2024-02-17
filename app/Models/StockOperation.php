<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOperation extends Model
{
    protected $table = "stock_operation";
    protected $fillable = ['stock_operation_type', 'bill_number', 'quantity', 'stock_id', 'item_id', 'customer', 'hijri_date'];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];
    use HasFactory;
}
