<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => 'admin_' . uniqid() . '@students.undip.ac.id',
            'password' => 'password123',
            'role' => 'ADMIN',
            'status' => 'ACTIVE',
        ]);
    }

    private function makePendingUser(string $email): User
    {
        return User::create([
            'name' => 'User Pending',
            'email' => $email,
            'password' => 'password123',
            'role' => 'USER',
            'status' => 'PENDING',
        ]);
    }

    public function test_register_with_students_domain_creates_pending_user(): void
    {
        $email = 'maba_' . uniqid() . '@students.undip.ac.id';

        $response = $this->post('/register', [
            'name' => 'Mahasiswa Baru',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertEquals('PENDING', User::where('email', $email)->first()->status);
    }

    public function test_register_with_undip_domain_creates_pending_user(): void
    {
        $email = 'dosen_' . uniqid() . '@undip.ac.id';

        $response = $this->post('/register', [
            'name' => 'Dosen Baru',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertEquals('PENDING', User::where('email', $email)->first()->status);
    }

    public function test_register_with_gmail_is_rejected(): void
    {
        $countBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'User Gmail',
            'email' => 'dian' . uniqid() . '@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Gunakan email resmi Universitas Diponegoro.',
        ]);
        $this->assertEquals($countBefore, User::count());
    }

    public function test_register_with_fake_undip_subdomain_is_rejected(): void
    {
        $countBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'User Palsu',
            'email' => 'user' . uniqid() . '@students.undip.ac.id.fake.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Gunakan email resmi Universitas Diponegoro.',
        ]);
        $this->assertEquals($countBefore, User::count());
    }

    public function test_rejected_reregister_still_requires_undip_domain(): void
    {
        $email = 'ulang_' . uniqid() . '@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'REJECTED', 'rejection_reason' => 'Data tidak valid.']);
        $countBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'Nama Baru',
            'email' => 'ulang' . uniqid() . '@gmail.com',
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Gunakan email resmi Universitas Diponegoro.',
        ]);
        $this->assertEquals($countBefore, User::count());
        $fresh = $user->refresh();
        $this->assertEquals('REJECTED', $fresh->status);
        $this->assertEquals('Data tidak valid.', $fresh->rejection_reason);
    }
}
