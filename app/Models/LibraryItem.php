<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryItem extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'isbn',
        'title',
        'author',
        'category',
        'faculty',
        'department',
        'edition',
        'year',
        'total_copies',
        'available_copies',
        'shelf_location',
        'is_digital',
        'digital_download_url',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'total_copies' => 'integer',
            'available_copies' => 'integer',
            'is_digital' => 'boolean',
        ];
    }

    public function borrowRecords(): HasMany
    {
        return $this->hasMany(LibraryBorrowRecord::class);
    }
}
