<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AcademicYearTest extends TestCase
{
    public int $idss = 1;

    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        $this->actingAs(User::find(2));

        $response = $this->post('/admin/academic-years', [
            'name' => '2024/2025'
        ]);

        $response->assertStatus(302)->assertSessionHas('success', 'Tahun ajaran 2024/2025 telah ditambahkan');
        $this->assertDatabaseHas('academic_years', [
            'id' => $this->idss,
            'name' => '2024/2025'
        ]);
        
    }

    public function test_update(): void
    {
        $this->actingAs(User::find(2));

        $response = $this->put("/admin/academic-years/$this->idss", [
            'name' => '2025/2026'
        ]);

        $response->assertStatus(302)->assertSessionHas('success', 'Tahun ajaran 2024/2025 telah diperbarui');
        $this->assertDatabaseHas('academic_years', [
            'id' => $this->idss,
            'name' => '2025/2026'
        ]);
    }

    // public function test_delete(): void
    // {
    //     $this->actingAs(User::first());

    //     $response = $this->delete('/admin/academic-years/'.$this->idss);

    //     $response->assertStatus(302)
    //     ->assertSessionHas('success', 'Tahun ajaran 2025/2026 telah dihapus');
        
    //     $this->assertDatabaseMissing('academic_years', [
    //         'id' => $this->idss
    //     ]);
    // }
}
