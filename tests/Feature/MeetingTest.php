<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeetingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_classroom(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->post('/admin/classrooms', [
            'academic_year_id' => 1,
            'name' => 'XI IPS 2'
        ]);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Kelas XI IPS 2 telah ditambahkan');

        $this->assertDatabaseHas('classrooms', [
            'id' => 1,
            'academic_year_id' => 1,
            'name' => 'XI IPS 2'
        ]);
    }

    public function test_create_subject(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->post('/admin/subjects', [
            'classroom_id' => 1,
            'semester_id' => 1,
            'name' => 'Pendidikan Kewarganegaraan',
        ]);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Mata pelajaran Pendidikan Kewarganegaraan telah ditambahkan');

        $this->assertDatabaseHas('subjects', [
            'id' => 1,
            'semester_id' => 1,
            'classroom_id' => 1,
            'name' => 'Pendidikan Kewarganegaraan',
            'kkm' => 70
        ]);
    }

    public function test_create(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->post('/admin/meetings', [
            'subject_id' => 1,
            'name' => 'Pertemuan ke-1',
        ]);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Pertemuan Pertemuan ke-1 telah ditambahkan');

        $this->assertDatabaseHas('meetings', [
            'id' => 1,
            'subject_id' => 1,
            'name' => 'Pertemuan ke-1'
        ]);
    }

    public function test_update(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->put('/admin/meetings/1', [
            'name' => 'Pertemuan ke-0',
        ]);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Pertemuan Pertemuan ke-0 telah diperbarui');

        $this->assertDatabaseHas('meetings', [
            'id' => 1,
            'subject_id' => 1,
            'name' => 'Pertemuan ke-0'
        ]);
    }

    public function test_delete(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $response = $this->delete('/admin/meetings/1');

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Pertemuan Pertemuan ke-0 telah dihapus');

        $this->assertDatabaseMissing('meetings', [
            'id' => 1,
            'subject_id' => 1,
            'name' => 'Pertemuan ke-0'
        ]);
    }
}
