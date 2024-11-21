<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'questions',
        'version',
    ];

    protected $casts = [
        'questions' => 'array', // Automatically cast JSON to array
    ];
}
