<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'question_id', 'challenge_id', 'attempt_number', 'selected_answer', 'is_correct'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function selectedAnswer()
    {
        return $this->belongsTo(Answer::class, 'selected_answer');
    }
}
