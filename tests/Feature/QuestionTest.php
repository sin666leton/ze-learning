<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_essay(): void
    {
        $this->actingAs(User::first());

        $response = $this->postJson('/admin/questions', [
            'quiz_id' => 1,
            'content' => 'Contoh soal',
            'type' => 'essay',
            'point' => 5
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ], [
                'data' => 1
            ]);

        $this->assertDatabaseHas('questions', [
            'quiz_id' => 1,
            'id' => 1,
            'content' => 'Contoh soal',
            'type' => 'essay',
            'point' => 5
        ]);
    }

    public function test_update_question(): void
    {
        $this->actingAs(User::first());

        $response = $this->putJson('/admin/questions/1', [
            'content' => 'Hasil dari 1 + 1 = ... ?',
            'type' => 'essay',
            'point' => 10
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'OK'
            ]);

        $this->assertDatabaseHas('questions', [
            'quiz_id' => 1,
            'id' => 1,
            'content' => 'Hasil dari 1 + 1 = ... ?',
            'type' => 'essay',
            'point' => 10
        ]);
    }

    public function test_create_mcq()
    {
        $this->actingAs(User::first());

        $response = $this->postJson('/admin/questions', [
            'quiz_id' => 1,
            'content' => 'Contoh soal pilihan ganda',
            'type' => 'mcq',
            'point' => 5,
            'choices' => [
                [
                    'content' => 'a'
                ],
                [
                    'content' => 'b'
                ],
                [
                    'content' => 'c'
                ]
            ],
            'answer' => 'd'    
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ], [
                'data' => 2
            ]);

        $this->assertDatabaseHas('questions', [
            'id' => 2,
            'content' => 'Contoh soal pilihan ganda',
            'type' => 'mcq',
            'point' => 5
        ]);

        $this->assertDatabaseHas('choices', [
            'question_id' => 2,
            'content' => 'a'
        ]);

        $this->assertDatabaseHas('choices', [
            'question_id' => 2,
            'content' => 'b'
        ]);

        $this->assertDatabaseHas('choices', [
            'question_id' => 2,
            'content' => 'c'
        ]);

        $this->assertDatabaseHas('answer_key', [
            'question_id' => 2,
            'content' => 'd'
        ]);
    }

    public function test_update_mcq()
    {
        $this->actingAs(User::first());

        $response = $this->putJson('/admin/questions/2', [
            'content' => 'Contoh',
            'type' => 'mcq',
            'point' => 10,
            'choices' => [
                [
                    'content' => 'e'
                ],
                [
                    'content' => 'f'
                ],
                [
                    'content' => 'g'
                ]
            ],
            'answer' => 'h'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'OK'
            ]);

        $this->assertDatabaseHas('questions', [
            'id' => 2,
            'content' => 'Contoh',
            'type' => 'mcq',
            'point' => 10
        ]);

        $this->assertDatabaseHas('choices', [
            'question_id' => 2,
            'content' => 'e'
        ]);

        $this->assertDatabaseHas('choices', [
            'question_id' => 2,
            'content' => 'f'
        ]);

        $this->assertDatabaseHas('choices', [
            'question_id' => 2,
            'content' => 'g'
        ]);

        $this->assertDatabaseHas('answer_key', [
            'question_id' => 2,
            'content' => 'h'
        ]);
    }

    public function test_delete_mcq()
    {
        $this->actingAs(User::first());

        $response = $this->deleteJson('/admin/questions/2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'Pertanyaan telah dihapus'
            ]);

        $this->assertDatabaseMissing('questions', [
            'id' => 2,
            'content' => 'Contoh',
            'type' => 'mcq',
            'point' => 10
        ]);

        $this->assertDatabaseMissing('choices', [
            'question_id' => 2,
            'content' => 'e'
        ]);

        $this->assertDatabaseMissing('choices', [
            'question_id' => 2,
            'content' => 'f'
        ]);

        $this->assertDatabaseMissing('choices', [
            'question_id' => 2,
            'content' => 'g'
        ]);

        $this->assertDatabaseMissing('answer_key', [
            'question_id' => 2,
            'content' => 'h'
        ]);
    }

    public function test_delete_essay(): void
    {
        $this->actingAs(User::first());

        $response = $this->deleteJson('/admin/questions/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'Pertanyaan telah dihapus'
            ]);

        $this->assertDatabaseMissing('questions', [
            'quiz_id' => 1,
            'id' => 1,
            'content' => 'Hasil dari 1 + 1 = ... ?',
            'type' => 'essay',
            'point' => 10
        ]);
    }
}
