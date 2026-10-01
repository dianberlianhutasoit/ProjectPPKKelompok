<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityAvailabilityTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_guest_can_view_facility_detail_and_slots(): void
    {
        $facility = $this->makeFacility();
        $owner = User::create([
            'name' => 'Nama Rahasia Pemohon',
            'email' => 'owner_'.uniqid().'@test.com',
            'password' => 'password123',
            'role' => 'USER',
            'status' => 'ACTIVE',
        ]);
        Reservation::create([
            'user_id' => $owner->id,
            'facility_id' => $facility->id,
            'participants' => 10,
            'start_time' => Carbon::tomorrow()->setHour(9)->setMinute(0),
            'end_time' => Carbon::tomorrow()->setHour(10)->setMinute(0),
            'purpose' => 'Tujuan Sangat Rahasia',
            'status' => 'PENDING',
        ]);

        $response = $this->get(route('facilities.show', [
            'facility' => $facility,
            'date' => Carbon::tomorrow()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertSee('Tersedia');
        $response->assertSee('Sudah dipesan');
        $response->assertDontSee('Nama Rahasia Pemohon');
        $response->assertDontSee('Tujuan Sangat Rahasia');
    }

    public function test_guest_cannot_store_reservation(): void
    {
        $facility = $this->makeFacility();

        $response = $this->post(route('reservations.store', $facility), [
            'date' => Carbon::tomorrow()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'participants' => 5,
            'purpose' => 'Coba reservasi guest',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('reservations', ['facility_id' => $facility->id]);
    }

    public function test_guest_cannot_view_inactive_facility(): void
    {
        $facility = $this->makeFacility('INACTIVE');

        $this->get(route('facilities.show', $facility))->assertNotFound();
    }

    public function test_user_can_still_view_facility_detail(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user_'.uniqid().'@test.com',
            'password' => 'password123',
            'role' => 'USER',
            'status' => 'ACTIVE',
        ]);
        $facility = $this->makeFacility();

        $this->actingAs($user)->get(route('facilities.show', $facility))->assertOk();
    }
}
