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
            'email' => 'admin_'.uniqid().'@students.undip.ac.id',
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

    public function test_admin_cannot_reject_without_reason(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makePendingUser('tolak_'.uniqid().'@students.undip.ac.id');

        $response = $this->actingAs($admin)->patch(
            route('admin.users.verify', $user),
            ['action' => 'reject']
        );

        $response->assertSessionHasErrors('rejection_reason');
        $this->assertEquals('PENDING', $user->refresh()->status);
        $this->assertNull($user->refresh()->rejection_reason);
    }

    public function test_admin_can_reject_with_reason(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makePendingUser('tolak_'.uniqid().'@students.undip.ac.id');

        $response = $this->actingAs($admin)->patch(
            route('admin.users.verify', $user),
            ['action' => 'reject', 'rejection_reason' => 'Gunakan email domain kampus UNDIP.']
        );

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $this->assertEquals('REJECTED', $user->refresh()->status);
        $this->assertEquals('Gunakan email domain kampus UNDIP.', $user->refresh()->rejection_reason);
    }

    public function test_admin_can_approve_pending_user(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makePendingUser('setuju_'.uniqid().'@students.undip.ac.id');

        $response = $this->actingAs($admin)->patch(
            route('admin.users.verify', $user),
            ['action' => 'approve']
        );

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $this->assertSame('ACTIVE', $user->refresh()->status);
        $this->assertNull($user->refresh()->rejection_reason);
    }

    public function test_only_pending_user_accounts_can_be_verified(): void
    {
        $admin = $this->makeAdmin();
        $cases = [
            ['USER', 'ACTIVE'],
            ['USER', 'REJECTED'],
            ['USER', 'INACTIVE'],
            ['STAFF', 'PENDING'],
            ['ADMIN', 'PENDING'],
        ];

        foreach ($cases as [$role, $status]) {
            $user = User::create([
                'name' => 'Target '.$role.' '.$status,
                'email' => strtolower($role).'_'.strtolower($status).'_'.uniqid().'@undip.ac.id',
                'password' => 'password123',
                'role' => $role,
                'status' => $status,
            ]);

            $response = $this->actingAs($admin)->patch(
                route('admin.users.verify', $user),
                ['action' => 'approve']
            );

            $response->assertSessionHasErrors([
                'user' => 'Hanya akun pengguna berstatus PENDING yang dapat diverifikasi.',
            ]);
            $this->assertSame($status, $user->refresh()->status);
        }
    }

    public function test_rejected_user_cannot_login(): void
    {
        $email = 'ditolak_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'REJECTED', 'rejection_reason' => 'Gunakan email domain kampus UNDIP.']);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_rejected_login_message_contains_reason(): void
    {
        $email = 'ditolak_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'REJECTED', 'rejection_reason' => 'Gunakan email domain kampus UNDIP.']);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Pendaftaran akun ditolak. Alasan: Gunakan email domain kampus UNDIP. Silakan daftar ulang setelah memperbaiki data.',
        ]);
    }

    public function test_rejected_login_without_reason_uses_fallback_message(): void
    {
        $email = 'ditolak_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'REJECTED', 'rejection_reason' => null]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Pendaftaran akun ditolak oleh admin.',
        ]);
    }

    public function test_rejected_user_can_reregister_with_same_email(): void
    {
        $email = 'ulang_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'REJECTED', 'rejection_reason' => 'Data tidak valid.']);

        $countBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'Nama Baru',
            'email' => $email,
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertEquals($countBefore, User::count());
    }

    public function test_reregister_reuses_record_and_updates_data(): void
    {
        $email = 'ulang_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'REJECTED', 'rejection_reason' => 'Data tidak valid.']);
        $oldId = $user->id;

        $this->post('/register', [
            'name' => 'Nama Baru',
            'email' => $email,
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $fresh = $user->refresh();

        $this->assertEquals($oldId, $fresh->id);
        $this->assertEquals('Nama Baru', $fresh->name);
        $this->assertEquals('USER', $fresh->role);
        $this->assertTrue(Hash::check('passwordbaru123', $fresh->password));
        $this->assertFalse(Hash::check('password123', $fresh->password));
        $this->assertEquals('PENDING', $fresh->status);
        $this->assertNull($fresh->rejection_reason);
    }

    public function test_pending_email_cannot_reregister(): void
    {
        $email = 'pending_'.uniqid().'@students.undip.ac.id';
        $this->makePendingUser($email);
        $countBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'Nama Lain',
            'email' => $email,
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email ini sudah terdaftar dan masih menunggu verifikasi admin.',
        ]);
        $this->assertEquals($countBefore, User::count());
    }

    public function test_active_email_cannot_reregister(): void
    {
        $email = 'aktif_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'ACTIVE']);
        $countBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'Nama Lain',
            'email' => $email,
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email ini sudah terdaftar. Silakan login.',
        ]);
        $this->assertEquals($countBefore, User::count());
    }

    public function test_inactive_email_cannot_reregister(): void
    {
        $email = 'nonaktif_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'INACTIVE']);
        $countBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'Nama Lain',
            'email' => $email,
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Akun dengan email ini dinonaktifkan. Silakan hubungi admin.',
        ]);
        $this->assertEquals($countBefore, User::count());
    }

    public function test_reregistered_user_can_login_after_approve(): void
    {
        $admin = $this->makeAdmin();
        $email = 'ulang_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'REJECTED', 'rejection_reason' => 'Data tidak valid.']);

        $this->post('/register', [
            'name' => 'Nama Baru',
            'email' => $email,
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $this->actingAs($admin)->patch(
            route('admin.users.verify', $user),
            ['action' => 'approve']
        );

        $fresh = $user->refresh();
        $this->assertEquals('ACTIVE', $fresh->status);
        $this->assertNull($fresh->rejection_reason);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'passwordbaru123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_register_with_students_domain_creates_pending_user(): void
    {
        $email = 'maba_'.uniqid().'@students.undip.ac.id';

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
        $email = 'dosen_'.uniqid().'@undip.ac.id';

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
            'email' => 'dian'.uniqid().'@gmail.com',
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
            'email' => 'user'.uniqid().'@students.undip.ac.id.fake.com',
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
        $email = 'ulang_'.uniqid().'@students.undip.ac.id';
        $user = $this->makePendingUser($email);
        $user->update(['status' => 'REJECTED', 'rejection_reason' => 'Data tidak valid.']);
        $countBefore = User::count();

        $response = $this->post('/register', [
            'name' => 'Nama Baru',
            'email' => 'ulang'.uniqid().'@gmail.com',
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
