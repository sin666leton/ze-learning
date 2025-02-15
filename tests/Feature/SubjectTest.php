<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    private int $id = 1;

    /**
     * A basic feature test example.
     */
    public function test_create()
    {
        $this->actingAs(User::first());

        $response = $this->post('/admin/subjects', [
            'classroom_id' => 1,
            'semester_id' => 1,
            'name' => 'Matematika',
            'kkm' => 75
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                "Matematika telah ditambahkan!"
            );

        $this->assertDatabaseHas('subjects', [
            'classroom_id' => 1,
            'semester_id' => 1,
            'name' => 'Matematika',
            'kkm' => 75
        ]);
    }

    public function test_update()
    {
        $this->actingAs(User::first());

        $response = $this->put('/admin/subjects/1', [
            'name' => 'PPKN',
            'kkm' => 75
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                "PPKN telah diperbarui!"
            );

        $this->assertDatabaseHas('subjects', [
            'classroom_id' => 1,
            'semester_id' => 1,
            'name' => 'PPKN',
            'kkm' => 75
        ]);
    }

    public function test_delete()
    {
        $this->actingAs(User::first());

        $response = $this->delete('/admin/subjects/1');

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                "PPKN telah dihapus!"
            );

        $this->assertDatabaseMissing('subjects', [
            'classroom_id' => 1,
            'semester_id' => 1,
            'name' => 'PPKN',
            'kkm' => 75
        ]);
    }
}
