<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserCreationTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => 'admin@undip.ac.id',
            'password' => 'password123',
            'role' => 'ADMIN',
            'status' => 'ACTIVE',
        ]);
    }

    private function createAccount(string $email, string $role)
    {
        return $this->actingAs($this->makeAdmin())->post(route('admin.users.store'), [
            'name' => 'Account Test',
            'email' => $email,
            'password' => 'password123',
            'role' => $role,
        ]);
    }

    public function test_admin_can_create_user_with_student_domain(): void
    {
        $this->createAccount('student@students.undip.ac.id', 'USER')
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'student@students.undip.ac.id',
            'role' => 'USER',
            'status' => 'ACTIVE',
        ]);
    }

    public function test_admin_can_create_user_with_undip_domain(): void
    {
        $this->createAccount('staff@undip.ac.id', 'USER')
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'staff@undip.ac.id',
            'role' => 'USER',
            'status' => 'ACTIVE',
        ]);
    }

    public function test_admin_can_create_staff_with_undip_domain(): void
    {
        $this->createAccount('staff@undip.ac.id', 'STAFF')
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'staff@undip.ac.id',
            'role' => 'STAFF',
            'status' => 'ACTIVE',
        ]);
    }

    public function test_admin_cannot_create_staff_with_student_domain(): void
    {
        $this->createAccount('student@students.undip.ac.id', 'STAFF')
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', [
            'email' => 'student@students.undip.ac.id',
        ]);
    }

    public function test_admin_cannot_create_admin_account(): void
    {
        $this->createAccount('newadmin@undip.ac.id', 'ADMIN')
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', [
            'email' => 'newadmin@undip.ac.id',
        ]);
    }
}
