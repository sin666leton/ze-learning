<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnswerAssignmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        $this->actingAs(User::find(2));

        // Storage::fake('public');

        $file = UploadedFile::fake()->createWithContent('tugas.pdf', "hello");

        $response = $this->post('/students/answer/assignments', [
            'assignment_id' => 1,
            'namespace' => 'namespace',
            'file' => $file
        ]);

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Kamu baru saja menyelesaikan tugas!');

        $this->assertDatabaseHas('answer_assignments', [
            'id' => 1,
            'student_classroom_id' => 1,
            'assignment_id' => 1,
            'namespace' => 'namespace.pdf'
        ]);
    }
}
