<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create()
    {
        $this->actingAs(User::first());
        
        $response = $this->post('/admin/students', [
            'email' => 'zidan@gmail.com',
            'name' => 'zidan',
            'password' => 'zidan',
            'nis' => 231011401979
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Pelajar 231011401979 telah ditambahkan'
            );

        $this->assertDatabaseHas('students', [
            'user_id' => 2,
            'nis' => 231011401979
        ]);

        $this->assertDatabaseHas('users', [
            'id' => 2,
            'email' => 'zidan@gmail.com',
            'name' => 'zidan',
        ]);
    }

    public function test_update()
    {
        $this->actingAs(User::first());
        
        $response = $this->put('/admin/students/1', [
            'email' => 'zidans@gmail.com',
            'name' => 'zidan',
            'nis' => 123
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Pelajar 123 telah diperbarui'
            );

        $this->assertDatabaseHas('students', [
            'user_id' => 2,
            'nis' => 123
        ]);

        $this->assertDatabaseHas('users', [
            'id' => 2,
            'email' => 'zidans@gmail.com',
            'name' => 'zidan',
        ]);
    }

    public function test_attach_classroom()
    {
        $this->actingAs(User::first());

        $response = $this->post('/admin/student-management/classrooms', [
            'student_id' => 1,
            'classroom_id' => 1
        ]);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Kelas telah ditambahkan ke siswa'
            );

        $this->assertDatabaseHas('student_classroom', [
            'id' => 1,
            'student_id' => 1,
            'classroom_id' => 1
        ]);
    }
    
    public function test_detach_classroom()
    {
        $this->actingAs(User::first());

        $response = $this->delete('/admin/student-management/classrooms/1/1');

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Kelas telah dihapus dari siswa'
            );

        $this->assertDatabaseMissing('student_classroom', [
            'id' => 1,
            'student_id' => 1,
            'classroom_id' => 1
        ]);
    }

    public function test_delete()
    {
        $this->actingAs(User::first());
        
        $response = $this->delete('/admin/students/1');

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                'Pelajar 123 telah dihapus'
            );

        $this->assertDatabaseMissing('students', [
            'user_id' => 2,
            'nis' => 123
        ]);
    }
    
}
