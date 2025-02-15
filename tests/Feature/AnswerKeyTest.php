<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerKeyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->postJson('/admin/answer_key', [
            'question_id' => 1,
            'content' => '2'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Kunci jawaban telah ditambahkan'
        ]);

        $this->assertDatabaseHas('answer_key', [
            'id' => 1,
            'question_id' => 1,
            'content' => '2'
        ]);
    }

    public function test_update(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->putJson('/admin/answer_key/1', [
            'content' => '20'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Kunci jawaban telah diperbarui'
        ]);

        $this->assertDatabaseHas('answer_key', [
            'id' => 1,
            'question_id' => 1,
            'content' => '20'
        ]);
    }

    public function test_delete(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->deleteJson('/admin/answer_key/1');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Kunci jawaban telah dihapus'
        ]);

        $this->assertDatabaseMissing('answer_key', [
            'id' => 1,
            'question_id' => 1,
            'content' => '20'
        ]);
    }
}
