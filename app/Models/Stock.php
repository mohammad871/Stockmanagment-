<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(mixed $data)
 */
class Stock extends Model
{
    protected $table = "stock";
    protected $fillable = ['name', 'hijri_date'];
    protected $hidden = [
        'created_at',
        "updated_at"
    ];

    use HasFactory;
}
