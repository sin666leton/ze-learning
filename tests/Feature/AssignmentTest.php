<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssignmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create()
    {
        $this->actingAs(User::first());

        $response = $this->post('/admin/assignments', [
            'subject_id' => 1,
            'title' => 'Penugasan pertama',
            'content' => 'Buat lah tugas blablabla',
            'access_at' => '2025-1-29T23:00:00',
            'ended_at' => '2025-1-30T23:00:00',
            'size' => 5
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Tugas telah ditambahkan'
            );

        $this->assertDatabaseHas('assignments', [
            'id' => 1,
            'title' => 'Penugasan pertama',
            'content' => 'Buat lah tugas blablabla'
        ]);
    }

    public function test_update()
    {
        $this->actingAs(User::first());

        $response = $this->put('/admin/assignments/1', [
            'title' => 'Penugasan pertama',
            'content' => 'Buat lah tugas',
            'access_at' => '2025-1-29T23:00:00',
            'ended_at' => '2025-1-30T23:00:00',
            'size' => 4
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Tugas telah diperbarui'
            );

        $this->assertDatabaseHas('assignments', [
            'id' => 1,
            'title' => 'Penugasan pertama',
            'content' => 'Buat lah tugas'
        ]);
    }

    public function test_delete()
    {
        $this->actingAs(User::first());

        $response = $this->delete('/admin/assignments/1');

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Tugas telah dihapus'
            );
    }
    
}
