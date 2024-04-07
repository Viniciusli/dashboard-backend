<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'photo',
    ];

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }
}
