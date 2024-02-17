<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = "transfer";
    protected $fillable = ['item_id', 'from_stock', 'to_stock', 'bill_number', 'quantity', 'hijri_date'];
    use HasFactory;
}
