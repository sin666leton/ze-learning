<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_a(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->postJson('/admin/choices', [
            'question_id' => 1,
            'content' => '4'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah ditambahkan'
        ]);

        $this->assertDatabaseHas('choices', [
            'id' => 1,
            'question_id' => 1,
            'content' => '4'
        ]);
    }

    public function test_create_b(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->postJson('/admin/choices', [
            'question_id' => 1,
            'content' => '1'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah ditambahkan'
        ]);

        $this->assertDatabaseHas('choices', [
            'id' => 2,
            'question_id' => 1,
            'content' => '1'
        ]);
    }

    public function test_create_c(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->postJson('/admin/choices', [
            'question_id' => 1,
            'content' => '3'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah ditambahkan'
        ]);

        $this->assertDatabaseHas('choices', [
            'id' => 3,
            'question_id' => 1,
            'content' => '3'
        ]);
    }
    public function test_create_d_fail(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->postJson('/admin/choices', [
            'question_id' => 1,
            'content' => '50'
        ]);

        $response->assertStatus(400)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda tidak lebih dari 3'
        ]);
    }

    public function test_update_a(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->putJson('/admin/choices/1', [
            'content' => '6'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah diperbarui'
        ]);

        $this->assertDatabaseHas('choices', [
            'id' => 1,
            'question_id' => 1,
            'content' => '6'
        ]);
    }

    public function test_update_b(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->putJson('/admin/choices/2', [
            'content' => '3'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah diperbarui'
        ]);

        $this->assertDatabaseHas('choices', [
            'id' => 2,
            'question_id' => 1,
            'content' => '3'
        ]);
    }

    public function test_update_c(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->putJson('/admin/choices/3', [
            'content' => '100'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah diperbarui'
        ]);

        $this->assertDatabaseHas('choices', [
            'id' => 3,
            'question_id' => 1,
            'content' => '100'
        ]);
    }

    public function test_delete_a(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->deleteJson('/admin/choices/1');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah dihapus'
        ]);

        $this->assertDatabaseMissing('choices', [
            'id' => 1,
        ]);
    }

    public function test_delete_b(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->deleteJson('/admin/choices/2');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah dihapus'
        ]);

        $this->assertDatabaseMissing('choices', [
            'id' => 2,
        ]);
    }
    public function test_delete_c(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $response = $this->deleteJson('/admin/choices/3');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ], [
            'message' => 'Pilihan ganda telah dihapus'
        ]);

        $this->assertDatabaseMissing('choices', [
            'id' => 3,
        ]);
    }
}
