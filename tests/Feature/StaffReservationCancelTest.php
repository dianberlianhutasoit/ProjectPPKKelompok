<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffReservationCancelTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

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

    private function makeReservation(string $status, ?Carbon $start = null): Reservation
    {
        $start = $start ?? now()->addDay()->setHour(9)->setMinute(0)->setSecond(0);

        return Reservation::create([
            'user_id' => $this->makeRequester()->id,
            'facility_id' => $this->makeFacility()->id,
            'participants' => 10,
            'start_time' => $start,
            'end_time' => $start->copy()->addHours(2),
            'purpose' => 'Keperluan test',
            'status' => $status,
        ]);
    }

    private function cancelAsStaff(User $staff, Reservation $reservation, array $payload = ['cancel_reason' => 'Darurat: gedung dipakai wisuda'])
    {
        return $this->actingAs($staff)->patch(
            route('staff.reservations.cancel', $reservation->id),
            $payload
        );
    }

    public function test_staff_can_cancel_approved_more_than_30_minutes_before_start(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 14, 29));
        $staff = $this->makeStaff();
        $reservation = $this->makeReservation('APPROVED', Carbon::create(2026, 10, 5, 15, 0));

        $response = $this->cancelAsStaff($staff, $reservation);

        $response->assertSessionHas('success');
        $this->assertEquals('CANCELLED', $reservation->refresh()->status);
        $this->assertEquals('Darurat: gedung dipakai wisuda', $reservation->refresh()->cancel_reason);
    }

    public function test_staff_can_cancel_approved_exactly_30_minutes_before_start(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 14, 30));
        $staff = $this->makeStaff();
        $reservation = $this->makeReservation('APPROVED', Carbon::create(2026, 10, 5, 15, 0));

        $response = $this->cancelAsStaff($staff, $reservation);

        $response->assertSessionHas('success');
        $this->assertEquals('CANCELLED', $reservation->refresh()->status);
    }

    public function test_staff_cannot_cancel_approved_less_than_30_minutes_before_start(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 14, 31));
        $staff = $this->makeStaff();
        $reservation = $this->makeReservation('APPROVED', Carbon::create(2026, 10, 5, 15, 0));

        $response = $this->cancelAsStaff($staff, $reservation);

        $response->assertSessionHasErrors('reservation');
        $this->assertEquals('APPROVED', $reservation->refresh()->status);
    }

    public function test_staff_cannot_cancel_approved_after_start(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 15, 30));
        $staff = $this->makeStaff();
        $reservation = $this->makeReservation('APPROVED', Carbon::create(2026, 10, 5, 15, 0));

        $response = $this->cancelAsStaff($staff, $reservation);

        $response->assertSessionHasErrors('reservation');
        $this->assertEquals('APPROVED', $reservation->refresh()->status);
    }

    public function test_staff_cancel_approved_without_reason_rejected(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 14, 0));
        $staff = $this->makeStaff();
        $reservation = $this->makeReservation('APPROVED', Carbon::create(2026, 10, 5, 15, 0));

        $response = $this->cancelAsStaff($staff, $reservation, ['cancel_reason' => '']);

        $response->assertSessionHasErrors('cancel_reason');
        $this->assertEquals('APPROVED', $reservation->refresh()->status);
    }

    public function test_staff_cannot_cancel_pending_via_cancel(): void
    {
        $staff = $this->makeStaff();
        $reservation = $this->makeReservation('PENDING');

        $response = $this->cancelAsStaff($staff, $reservation, ['cancel_reason' => 'Coba batalkan pending']);

        $response->assertSessionHasErrors('reservation');
        $this->assertEquals('PENDING', $reservation->refresh()->status);
    }

    public function test_staff_cannot_cancel_rejected_or_cancelled(): void
    {
        $staff = $this->makeStaff();

        foreach (['REJECTED', 'CANCELLED'] as $status) {
            $reservation = $this->makeReservation($status);

            $response = $this->cancelAsStaff($staff, $reservation, ['cancel_reason' => 'Coba batalkan lagi']);

            $response->assertSessionHasErrors('reservation');
            $this->assertEquals($status, $reservation->refresh()->status);
        }
    }

    public function test_staff_index_shows_cancel_for_approved(): void
    {
        $staff = $this->makeStaff();
        $this->makeReservation('APPROVED');

        $response = $this->actingAs($staff)->get(
            route('staff.reservations.index', ['status' => 'APPROVED'])
        );

        $response->assertOk();
        $response->assertSee('Batalkan');
        $response->assertSee('Alasan pembatalan');
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
