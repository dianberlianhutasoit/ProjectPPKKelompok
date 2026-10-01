<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCancelDeadlineTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'User Test',
            'email' => 'user_'.uniqid().'@test.com',
            'password' => 'password123',
            'role' => 'USER',
            'status' => 'ACTIVE',
        ]);
    }

    private function makeReservation(User $owner, string $status, Carbon $start): Reservation
    {
        $facility = Facility::create([
            'name' => 'Ruang Test '.uniqid(),
            'type' => 'Ruang',
            'location' => 'Gedung A',
            'capacity' => 50,
            'status' => 'AVAILABLE',
        ]);

        return Reservation::create([
            'user_id' => $owner->id,
            'facility_id' => $facility->id,
            'participants' => 10,
            'start_time' => $start,
            'end_time' => $start->copy()->addHour(),
            'purpose' => 'Keperluan test',
            'status' => $status,
        ]);
    }

    public function test_owner_can_cancel_pending_more_than_2_hours_before(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 8, 0));
        $owner = $this->makeUser();
        $reservation = $this->makeReservation($owner, 'PENDING', Carbon::create(2026, 10, 5, 12, 0));

        $response = $this->actingAs($owner)->patch(route('reservations.cancel', $reservation));

        $response->assertSessionHas('success');
        $this->assertEquals('CANCELLED', $reservation->refresh()->status);
    }

    public function test_owner_cannot_cancel_pending_less_than_2_hours_before(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 10, 30));
        $owner = $this->makeUser();
        $reservation = $this->makeReservation($owner, 'PENDING', Carbon::create(2026, 10, 5, 12, 0));

        $response = $this->actingAs($owner)->patch(route('reservations.cancel', $reservation));

        $response->assertSessionHasErrors('reservation');
        $this->assertEquals('PENDING', $reservation->refresh()->status);
    }

    public function test_other_user_cannot_cancel(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 8, 0));
        $owner = $this->makeUser();
        $other = $this->makeUser();
        $reservation = $this->makeReservation($owner, 'PENDING', Carbon::create(2026, 10, 5, 12, 0));

        $this->actingAs($other)->patch(route('reservations.cancel', $reservation))->assertForbidden();
        $this->assertEquals('PENDING', $reservation->refresh()->status);
    }

    public function test_user_cannot_cancel_approved(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 8, 0));
        $owner = $this->makeUser();
        $reservation = $this->makeReservation($owner, 'APPROVED', Carbon::create(2026, 10, 5, 12, 0));

        $response = $this->actingAs($owner)->patch(route('reservations.cancel', $reservation));

        $response->assertSessionHasErrors('reservation');
        $this->assertEquals('APPROVED', $reservation->refresh()->status);
    }
}
