<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffReservationCancelTest extends TestCase
{
    use RefreshDatabase;

    private function makeStaff(): User
    {
        return User::create([
            'name' => 'Staff Test',
            'email' => 'staff_'.uniqid().'@test.com',
            'password' => 'password123',
            'role' => 'STAFF',
            'status' => 'ACTIVE',
        ]);
    }

    private function makeRequester(): User
    {
        return User::create([
            'name' => 'User Test',
            'email' => 'user_'.uniqid().'@test.com',
            'password' => 'password123',
            'role' => 'USER',
            'status' => 'ACTIVE',
        ]);
    }

    private function makeFacility(): Facility
    {
        return Facility::create([
            'name' => 'Ruang Test',
            'type' => 'Ruang',
            'location' => 'Gedung A',
            'capacity' => 50,
            'status' => 'AVAILABLE',
        ]);
    }

    private function makeReservation(string $status): Reservation
    {
        return Reservation::create([
            'user_id' => $this->makeRequester()->id,
            'facility_id' => $this->makeFacility()->id,
            'participants' => 10,
            'start_time' => now()->addDay()->setHour(9)->setMinute(0),
            'end_time' => now()->addDay()->setHour(11)->setMinute(0),
            'purpose' => 'Keperluan test',
            'status' => $status,
        ]);
    }

    public function test_staff_cannot_cancel_approved_reservation(): void
    {
        $staff = $this->makeStaff();
        $reservation = $this->makeReservation('APPROVED');

        $response = $this->actingAs($staff)->patch(
            route('staff.reservations.cancel', $reservation->id),
            ['cancel_reason' => 'Coba batalkan approved']
        );

        $response->assertSessionHasErrors('reservation');
        $this->assertEquals('APPROVED', $reservation->refresh()->status);
        $this->assertDatabaseMissing('reservations', [
            'id' => $reservation->id,
            'status' => 'CANCELLED',
        ]);
    }

    public function test_staff_can_cancel_pending_reservation(): void
    {
        $staff = $this->makeStaff();
        $reservation = $this->makeReservation('PENDING');

        $response = $this->actingAs($staff)->patch(
            route('staff.reservations.cancel', $reservation->id),
            ['cancel_reason' => 'Alasan staff batalkan']
        );

        $response->assertSessionHas('success');
        $this->assertEquals('CANCELLED', $reservation->refresh()->status);
        $this->assertEquals('Alasan staff batalkan', $reservation->refresh()->cancel_reason);
    }

    public function test_staff_index_hides_cancel_for_approved(): void
    {
        $staff = $this->makeStaff();
        $this->makeReservation('APPROVED');

        $response = $this->actingAs($staff)->get(
            route('staff.reservations.index', ['status' => 'APPROVED'])
        );

        $response->assertOk();
        $response->assertDontSee('Batalkan');
        $response->assertDontSee('Alasan pembatalan');
    }

    public function test_staff_index_shows_approve_and_reject_for_pending(): void
    {
        $staff = $this->makeStaff();
        $this->makeReservation('PENDING');

        $response = $this->actingAs($staff)->get(
            route('staff.reservations.index', ['status' => 'PENDING'])
        );

        $response->assertOk();
        $response->assertSee('Setujui');
        $response->assertSee('Tolak');
    }
}
