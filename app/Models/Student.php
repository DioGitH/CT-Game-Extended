<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Achievement;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'nim',
        'address',
        'birth_date',
        'religion',
        'gender',
        'phone_number',
        'prodi',
        'semester',
        'class',
        'streak',
        'exp',
        'lives',
        'weekly_score',
        'total_score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ranks()
    {
        return $this->belongsToMany(Rank::class, 'student_rank', 'student_id', 'rank_id')->withTimestamps();
    }

    public function updateRank()
    {
        $newRank = Rank::where('min_exp', '<=', $this->exp)
            ->where('max_exp', '>=', $this->exp)
            ->orderBy('min_exp', 'desc')
            ->first();

        if (!$newRank) {
            return [
                'rank_changed' => false,
                'previous_rank_id' => null,
                'new_rank_id' => null
            ];
        }

        $latestRank = $this->ranks()->orderByDesc('ranks.min_exp')->first();

        if (!$latestRank || $latestRank->id !== $newRank->id) {
            if (!$this->ranks->contains($newRank->id)) {
                $this->ranks()->attach($newRank->id, ['received_at' => now()]);
            }

            return [
                'rank_changed' => true,
                'previous_rank_id' => $latestRank?->id,
                'new_rank_id' => $newRank->id
            ];
        }

        return [
            'rank_changed' => false,
            'previous_rank_id' => $latestRank?->id,
            'new_rank_id' => $newRank->id
        ];
    }

    public function getCurrentRankAttribute()
    {
        return $this->ranks->sortByDesc('min_exp')->first();
    }

    public function currentSection()
    {
        return $this->belongsTo(Section::class, 'current_section_id');
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'student_achievement')
            ->withPivot('unlocked_at');
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'user_id');
    }

    public function challengeResults()
    {
        return $this->hasMany(ChallengeResult::class, 'user_id');
    }

    public function currentChallenge()
    {
        return $this->belongsTo(Challenge::class, 'current_challenge_id');
    }
}
