<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuizTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create()
    {
        $this->actingAs(User::first());

        $response = $this->post('/admin/quizzes', [
            'subject_id' => 1,
            'title' => 'Kuis pertama',
            'content' => 'Deskripsi kuis',
            'duration' => 120,
            'access_at' => '2025-1-29T23:00:00',
            'ended_at' => '2025-1-30T23:00:00'
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Kuis telah ditambahkan'
            );

        $this->assertDatabaseHas('quizzes', [
            'subject_id' => 1,
            'id' => 1,
            'title' => 'Kuis pertama',
            'content' => 'Deskripsi kuis',
            'duration' => 120,
        ]);
    }

    public function test_update()
    {
        $this->actingAs(User::first());

        $response = $this->put('/admin/quizzes/1', [
            'title' => 'Kuis owe',
            'content' => 'Deskripsi kuis',
            'duration' => 60,
            'access_at' => '2025-1-29T23:00:00',
            'ended_at' => '2025-1-30T23:00:00'
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Kuis telah diperbarui'
            );

        $this->assertDatabaseHas('quizzes', [
            'subject_id' => 1,
            'id' => 1,
            'title' => 'Kuis owe',
            'content' => 'Deskripsi kuis',
            'duration' => 60,
        ]);
    }

    public function test_delete()
    {
        $this->actingAs(User::first());

        $response = $this->delete('/admin/quizzes/1');
        
        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Kuis telah dihapus'
            );

        $this->assertDatabaseMissing('quizzes', [
            'id' => 1
        ]);
    }
}
