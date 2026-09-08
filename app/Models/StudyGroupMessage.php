<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyGroupMessage extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'study_group_id',
        'student_id',
        'message',
        'attachment_url',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class, 'study_group_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
