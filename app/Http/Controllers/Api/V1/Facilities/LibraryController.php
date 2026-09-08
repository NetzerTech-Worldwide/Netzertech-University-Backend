<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Controller;
use App\Models\LibraryBorrowRecord;
use App\Models\LibraryItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class LibraryController extends Controller
{
    use ApiResponse;

    /**
     * Search and list central university library catalog.
     */
    public function catalog(Request $request): JsonResponse
    {
        $query = LibraryItem::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('author', 'LIKE', "%{$search}%")
                    ->orWhere('isbn', 'LIKE', "%{$search}%");
            });
        }

        if ($category = $request->query('category')) {
            if ($category !== 'all') {
                $query->where('category', $category);
            }
        }

        if ($faculty = $request->query('faculty')) {
            if ($faculty !== 'All Faculties') {
                $query->where('faculty', $faculty);
            }
        }

        $items = $query->orderBy('title')->get();

        return $this->success($items, 'Library catalog items retrieved.');
    }

    /**
     * Reserve a book for borrowing.
     */
    public function reserve(Request $request): JsonResponse
    {
        $request->validate([
            'library_item_id' => 'required|exists:library_items,id',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Only students can reserve library books.', HttpStatus::HTTP_FORBIDDEN);
        }

        return DB::transaction(function () use ($student, $request) {
            $item = LibraryItem::where('id', $request->input('library_item_id'))->lockForUpdate()->firstOrFail();

            if ($item->available_copies <= 0) {
                return $this->error('No physical copies currently available for reservation.', HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Check if student already holds active reservation for this book
            $existing = LibraryBorrowRecord::where('student_id', $student->id)
                ->where('library_item_id', $item->id)
                ->whereIn('status', ['reserved', 'borrowed'])
                ->first();

            if ($existing) {
                return $this->error('You already have an active loan or reservation for this item.', HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
            }

            $item->decrement('available_copies');

            $record = LibraryBorrowRecord::create([
                'university_id' => $student->university_id,
                'student_id' => $student->id,
                'library_item_id' => $item->id,
                'borrow_date' => now()->toDateString(),
                'due_date' => now()->addDays(14)->toDateString(),
                'status' => 'reserved',
            ]);

            return $this->success($record->load('item'), 'Book reserved successfully. Please pick up at the circulation desk within 48 hours.', HttpStatus::HTTP_CREATED);
        });
    }

    /**
     * View active and past borrow records for the authenticated student.
     */
    public function myBorrowed(Request $request): JsonResponse
    {
        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_NOT_FOUND);
        }

        $records = LibraryBorrowRecord::where('student_id', $student->id)
            ->with('item')
            ->latest('borrow_date')
            ->get();

        return $this->success($records, 'Student library loan records retrieved.');
    }

    /**
     * Return a borrowed book (Circulation Librarian).
     */
    public function returnBook(int $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $record = LibraryBorrowRecord::with('item')->findOrFail($id);

            if ($record->status === 'returned') {
                return $this->error('This book has already been marked as returned.', HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
            }

            $record->update([
                'return_date' => now()->toDateString(),
                'status' => 'returned',
            ]);

            $record->item->increment('available_copies');

            return $this->success($record, 'Book loan returned successfully.');
        });
    }
}
