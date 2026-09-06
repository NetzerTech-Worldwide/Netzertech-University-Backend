<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostUtmeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'exam_date',
        'exam_time',
        'venue',
        'seat_number',
        'is_attended',
        'score_obtained',
    ];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'is_attended' => 'boolean',
            'score_obtained' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(AdmissionsApplication::class, 'application_id');
    }
}
