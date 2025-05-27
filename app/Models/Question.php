<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'challenge_id',
        'type',
        'description',
        'question_text',
        'question_image',
        'score',
        'exp',
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
