<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Audit URL bypass / RBAC untuk scope Person-1 (Backend & Database).
// Skenario yang sudah dicakup test lain tidak diulang di sini.
class RbacTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, string $status = 'ACTIVE'): User
    {
        return User::create([
            'name' => $role.' Test',
            'email' => strtolower($role).'_'.uniqid().'@test.com',
            'password' => 'password123',
            'role' => $role,
            'status' => $status,
        ]);
    }

    private function makeFacility(string $status = 'AVAILABLE'): Facility
    {
        return Facility::create([
            'name' => 'Ruang Test '.uniqid(),
            'type' => 'Ruang',
            'location' => 'Gedung A',
            'capacity' => 50,
            'status' => $status,
        ]);
    }

    public function test_user_cannot_access_admin_routes(): void
    {
        $user = $this->makeUser('USER');

        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.users.create'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.users.store'), [
            'name' => 'X', 'email' => 'x@test.com', 'password' => 'password123', 'role' => 'USER',
        ])->assertForbidden();
        $this->actingAs($user)->patch(route('admin.users.verify', $user), [
            'action' => 'approve',
        ])->assertForbidden();
        $this->actingAs($user)->delete(route('admin.users.destroy', $user))->assertForbidden();
    }

    public function test_staff_cannot_access_admin_routes(): void
    {
        $staff = $this->makeUser('STAFF');
        $target = $this->makeUser('USER');

        $this->actingAs($staff)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($staff)->patch(route('admin.users.verify', $target), [
            'action' => 'approve',
        ])->assertForbidden();
    }

    public function test_staff_cannot_access_user_reservation_routes(): void
    {
        $staff = $this->makeUser('STAFF');
        $facility = $this->makeFacility();

        $this->actingAs($staff)->get(route('reservations.create', $facility))->assertForbidden();
        $this->actingAs($staff)->get(route('reservations.index'))->assertForbidden();
    }

    public function test_guest_cannot_access_authenticated_routes(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('reservations.index'))->assertRedirect(route('login'));
        $this->get(route('reports.index'))->assertRedirect(route('login'));
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
    }

    public function test_non_active_user_cannot_access_active_routes(): void
    {
        $facility = $this->makeFacility();

        foreach (['PENDING', 'REJECTED', 'INACTIVE'] as $status) {
            $user = $this->makeUser('USER', $status);

            $this->actingAs($user)->get(route('reservations.index'))->assertRedirect(route('login'));
            $this->actingAs($user)->get(route('reservations.create', $facility))->assertRedirect(route('login'));
        }
    }

    public function test_inactive_facility_cannot_be_reserved(): void
    {
        $user = $this->makeUser('USER');
        $facility = $this->makeFacility('INACTIVE');

        $this->actingAs($user)->get(route('reservations.create', $facility))
            ->assertRedirect(route('facilities.show', $facility));

        $this->actingAs($user)->post(route('reservations.store', $facility), [
            'date' => Carbon::tomorrow()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'participants' => 5,
            'purpose' => 'Coba reservasi INACTIVE',
        ])->assertSessionHasErrors('facility');

        $this->assertDatabaseMissing('reservations', ['facility_id' => $facility->id]);
    }

    public function test_maintenance_facility_cannot_be_reserved(): void
    {
        $user = $this->makeUser('USER');
        $facility = $this->makeFacility('MAINTENANCE');

        $this->actingAs($user)->post(route('reservations.store', $facility), [
            'date' => Carbon::tomorrow()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'participants' => 5,
            'purpose' => 'Coba reservasi MAINTENANCE',
        ])->assertSessionHasErrors('facility');

        $this->assertDatabaseMissing('reservations', ['facility_id' => $facility->id]);
    }
}
