<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OLevelResult extends Model
{
    use HasFactory;

    protected $table = 'olevel_results';

    protected $fillable = [
        'application_id',
        'sitting_number',
        'exam_type',
        'year',
        'exam_number',
        'centre_number',
        'subjects',
    ];

    protected function casts(): array
    {
        return [
            'sitting_number' => 'integer',
            'year' => 'integer',
            'subjects' => 'array',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(AdmissionsApplication::class, 'application_id');
    }
}
