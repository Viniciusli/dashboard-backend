<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'exhibition_id',
        'file',
        'file_original',
        'lang',
    ];

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }
}
