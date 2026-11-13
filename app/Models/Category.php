<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Columns Eloquent is allowed to fill from form input.
     * Anything not listed here is silently ignored, which protects against
     * a user sneakily posting extra fields.
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
