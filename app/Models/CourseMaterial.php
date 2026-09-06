<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseMaterial extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'course_id',
        'uploader_staff_id',
        'title',
        'description',
        'file_url',
        'file_type',
        'file_size_kb',
        'week_number',
    ];

    protected function casts(): array
    {
        return [
            'file_size_kb' => 'integer',
            'week_number' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'uploader_staff_id');
    }
}
