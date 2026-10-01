<?php

namespace Tests\Feature;

use App\Http\Requests\ReservationRequest;
use App\Models\Facility;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTimeRuleTest extends TestCase
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

    public function test_rounding_to_next_30_minute_slot(): void
    {
        // 10:10 + 2 jam = 12:10 -> 12:30
        $this->assertEquals('12:30', ReservationRequest::roundedMinimumStart(Carbon::create(2026, 10, 5, 10, 10))->format('H:i'));
        // Tepat di slot -> tidak berubah
        $this->assertEquals('12:30', ReservationRequest::roundedMinimumStart(Carbon::create(2026, 10, 5, 10, 30))->format('H:i'));
        $this->assertEquals('12:00', ReservationRequest::roundedMinimumStart(Carbon::create(2026, 10, 5, 10, 0))->format('H:i'));
        // Lewat :30 -> jam berikutnya
        $this->assertEquals('13:00', ReservationRequest::roundedMinimumStart(Carbon::create(2026, 10, 5, 10, 31))->format('H:i'));
        $this->assertEquals('13:00', ReservationRequest::roundedMinimumStart(Carbon::create(2026, 10, 5, 10, 45))->format('H:i'));
    }

    public function test_same_day_less_than_2_hours_rejected(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 10, 10));
        $user = $this->makeUser();
        $facility = $this->makeFacility();

        $response = $this->actingAs($user)->post(route('reservations.store', $facility), [
            'date' => '2026-10-05',
            'start_time' => '12:00', // minimum valid 12:30
            'end_time' => '12:30',
            'participants' => 5,
            'purpose' => 'Rapat',
        ]);

        $response->assertSessionHasErrors('start_time');
        $this->assertDatabaseMissing('reservations', ['facility_id' => $facility->id]);
    }

    public function test_same_day_at_minimum_accepted(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 10, 10));
        $user = $this->makeUser();
        $facility = $this->makeFacility();

        $response = $this->actingAs($user)->post(route('reservations.store', $facility), [
            'date' => '2026-10-05',
            'start_time' => '12:30',
            'end_time' => '13:00',
            'participants' => 5,
            'purpose' => 'Rapat',
        ]);

        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservations', [
            'facility_id' => $facility->id,
            'status' => 'PENDING',
        ]);
    }

    public function test_tomorrow_not_affected_by_2_hour_rule(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 19, 0));
        $user = $this->makeUser();
        $facility = $this->makeFacility();

        $response = $this->actingAs($user)->post(route('reservations.store', $facility), [
            'date' => '2026-10-06',
            'start_time' => '07:00',
            'end_time' => '07:30',
            'participants' => 5,
            'purpose' => 'Rapat besok',
        ]);

        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservations', [
            'facility_id' => $facility->id,
            'purpose' => 'Rapat besok',
        ]);
    }

    public function test_same_day_rejected_when_minimum_past_operating_hours(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 5, 18, 0)); // minimum 20:00 > 19:30
        $user = $this->makeUser();
        $facility = $this->makeFacility();

        $response = $this->actingAs($user)->post(route('reservations.store', $facility), [
            'date' => '2026-10-05',
            'start_time' => '19:30',
            'end_time' => '20:00',
            'participants' => 5,
            'purpose' => 'Rapat malam',
        ]);

        $response->assertSessionHasErrors('date');
        $this->assertDatabaseMissing('reservations', ['facility_id' => $facility->id]);
    }
}
