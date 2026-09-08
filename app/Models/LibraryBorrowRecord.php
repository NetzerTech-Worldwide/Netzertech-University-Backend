<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryBorrowRecord extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'student_id',
        'library_item_id',
        'borrow_date',
        'due_date',
        'return_date',
        'fine_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
            'fine_amount' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(LibraryItem::class, 'library_item_id');
    }
}
