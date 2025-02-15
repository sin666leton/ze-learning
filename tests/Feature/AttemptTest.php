<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttemptTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_successfully(): void
    {
        $this->actingAs(User::find(2));
        $response = $this->postJson('/students/attempt/quiz', [
            'quiz_id' => 1
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Berhasil'
        ]);

        $this->assertDatabaseHas('attempts', [
            'id' => 1,
            'student_id' => 1,
            'quiz_id' => 1
        ]);
    }

    public function test_create_conflict(): void
    {
        $this->actingAs(User::find(2));
        $response = $this->postJson('/students/attempt/quiz', [
            'quiz_id' => 1
        ]);

        $response->assertStatus(409)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Kamu sedang mengerjakan kuis yang lain'
        ]);

        $this->assertDatabaseMissing('attempts', [
            'id' => 2,
            'student_id' => 1,
            'quiz_id' => 1
        ]);
    }

    // public function test_delete(): void
    // {
    //     $this->actingAs(User::find(2));
    //     $response = $this->deleteJson('/students/attempt/quiz/1');

    //     $response->assertStatus(200)
    //     ->assertJsonStructure([
    //         'message'
    //     ], [
    //         'message' => 'Berhasil'
    //     ]);

    //     $this->assertDatabaseMissing('attempts', [
    //         'id' => 1,
    //         'student_id' => 1,
    //         'quiz_id' => 1
    //     ]);
    // }
}
