<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use hasFactory;

    protected $fillable = [
        'title',
        'file_name',
        'file_type',
        'file_location',
        'version',
    ];
}
