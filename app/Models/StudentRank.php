<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRank extends Pivot
{
    use HasFactory;

    protected $table = 'student_rank';

    protected $fillable = ['student_id', 'rank_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }
}
