<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'answers',
        'question_id',
    ];

    protected $casts = [
        'answers' => 'array', // Automatically cast JSON to array
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

