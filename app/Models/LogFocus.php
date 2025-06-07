<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogFocus extends Model
{
    use HasFactory;

    protected $table = 'log_focus';

    protected $fillable = ['user_id', 'challengeresult_id', 'duration', 'start_at', 'end_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function challengeResult()
    {
        return $this->belongsTo(ChallengeResult::class, 'challengeresult_id');
    }
}
