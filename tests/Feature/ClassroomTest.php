<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    private int $ids = 1;

    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        $this->actingAs(User::first());

        $response = $this->post('/admin/classrooms', [
            'academic_year_id' => 1,
            'name' => 'XI IPS 2'
        ]);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Kelas XI IPS 2 telah ditambahkan');

        $this->assertDatabaseHas('classrooms', [
            'id' => $this->ids,
            'academic_year_id' => 1,
            'name' => 'XI IPS 2'
        ]);
    }

    public function test_update(): void
    {
        $this->actingAs(User::first());

        $response = $this->put('/admin/classrooms/'.$this->ids, [
            'name' => 'XI IPS 1'
        ]);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Kelas XI IPS 1 telah diperbarui');

        $this->assertDatabaseHas('classrooms', [
            'id' => $this->ids,
            'academic_year_id' => 1,
            'name' => 'XI IPS 1'
        ]);
    }

    // public function test_delete(): void
    // {
    //     $this->actingAs(User::first());

    //     $response = $this->delete('/admin/classrooms/'.$this->ids);

    //     $response->assertStatus(302)
    //     ->assertSessionHas('success', 'Kelas XI IPS 1 telah dihapus');

    //     $this->assertDatabaseMissing('classrooms', [
    //         'id' => $this->ids,
    //         'academic_year_id' => 1,
    //         'name' => 'XI IPS 1'
    //     ]);
    // }
}
