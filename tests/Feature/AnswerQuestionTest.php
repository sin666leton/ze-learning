<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerQuestionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_answer_mcq_successfully(): void
    {
        $this->actingAs(User::find(2));

        $response = $this->postJson('/students/answer/questions', [
            'question_id' => 1,
            'content' => '2'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'Berhasil'
            ]);

        $this->assertDatabaseHas('answer_questions', [
            'id' => 1,
            'student_id' => 1,
            'content' => '2',
            'is_correct' => true
        ]);
    }

    public function test_answer_mcq_successfully_incorrect(): void
    {
        $this->actingAs(User::find(2));

        $response = $this->postJson('/students/answer/questions', [
            'question_id' => 1,
            'content' => '3'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'Berhasil'
            ]);

        $this->assertDatabaseHas('answer_questions', [
            'id' => 1,
            'student_id' => 1,
            'content' => '3',
            'is_correct' => false
        ]);
    }

    public function test_answer_essay_successfully()
    {
        $this->actingAs(User::find(2));

        $response = $this->postJson('/students/answer/questions', [
            'question_id' => 2,
            'content' => '3'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'Berhasil'
            ]);

        $this->assertDatabaseHas('answer_questions', [
            'id' => 2,
            'student_id' => 1,
            'content' => '3',
            'is_correct' => false
        ]);
    }

    public function test_answer_unauthorized()
    {
        $this->actingAs(User::find(1));

        $response = $this->postJson('/students/answer/questions', [
            'question_id' => 1,
            'content' => '3'
        ]);

        $response->assertStatus(403)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'unauthorized'
            ]);
    }

    public function test_answer_notFound()
    {
        $this->actingAs(User::find(2));

        $response = $this->postJson('/students/answer/questions', [
            'question_id' => 3,
            'content' => '3'
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'Soal tidak valid'
            ]);
    }

    public function test_delete_attempt()
    {
        $this->actingAs(User::find(2));

        $response = $this->deleteJson('/students/attempt/quiz/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ], [
                'message' => 'Berhasil'
            ]);

        $this->assertDatabaseMissing('attempts', [
            'id' => 1
        ]);
    }
}
