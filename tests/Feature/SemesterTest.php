<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SemesterTest extends TestCase
{
    private int $ids = 1;

    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        $this->actingAs(User::first());
        
        $response = $this->post('/admin/semesters', [
            'academic_year_id' => 1,
            'name' => 'Semester 1',
            'start' => '2025/07/01',
            'end' => '2025/12/01'
        ]);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Semester 1 telah ditambahkan!');

        $this->assertDatabaseHas('semesters', [
            'id' => $this->ids,
            'academic_year_id' => 1,
            'name' => 'Semester 1',
            'start' => '2025/07/01',
            'end' => '2025/12/01'
        ]);
    }

    public function test_update(): void
    {
        $this->actingAs(User::first());
        
        $response = $this->put('/admin/semesters/'.$this->ids, [
            'name' => 'Semester 2',
            'start' => '2026/01/01',
            'end' => '2026/06/01'
        ]);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Semester 2 telah diperbarui!');

        $this->assertDatabaseHas('semesters', [
            'id' => $this->ids,
            'academic_year_id' => 1,
            'name' => 'Semester 2',
            'start' => '2026/01/01',
            'end' => '2026/06/01'
        ]);
    }

    public function test_delete(): void
    {
        $this->actingAs(User::first());
        
        $response = $this->delete('/admin/semesters/'.$this->ids);

        $response->assertStatus(302)
        ->assertSessionHas('success', 'Semester 2 telah dihapus!');

        $this->assertDatabaseMissing('semesters', [
            'id' => $this->ids,
            'academic_year_id' => 1
        ]);
    }
}
