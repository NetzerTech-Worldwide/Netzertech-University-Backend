<?php

namespace Tests\Feature;

use App\Models\LibraryBorrowRecord;
use App\Models\LibraryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibraryCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_search_and_filter_library_catalog(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/facilities/library/catalog?search=Algorithms');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');
    }


    public function test_student_can_reserve_an_available_book(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        // Pick a book that Chidi hasn't borrowed yet
        $book = LibraryItem::where('title', 'LIKE', '%Operating System%')->first();
        $initialAvailable = $book->available_copies;

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/facilities/library/reserve', [
                'library_item_id' => $book->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'reserved');

        $this->assertEquals($initialAvailable - 1, $book->fresh()->available_copies);
    }

    public function test_student_cannot_double_reserve_the_same_book(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $alreadyBorrowed = LibraryBorrowRecord::where('student_id', $studentUser->student->id)->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/facilities/library/reserve', [
                'library_item_id' => $alreadyBorrowed->library_item_id,
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonFragment(['message' => 'You already have an active loan or reservation for this item.']);
    }

    public function test_librarian_can_process_book_return_and_restore_inventory(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $borrowRecord = LibraryBorrowRecord::where('student_id', $studentUser->student->id)->first();
        $book = $borrowRecord->item;
        $copiesBefore = $book->available_copies;

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson("/api/v1/facilities/library/loans/{$borrowRecord->id}/return");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'returned');

        $this->assertEquals($copiesBefore + 1, $book->fresh()->available_copies);
    }
}
