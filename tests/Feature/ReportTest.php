<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role): User
    {
        return User::create([
            'name' => $role.' Test',
            'email' => strtolower($role).'_'.uniqid().'@test.com',
            'password' => 'password123',
            'role' => $role,
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

    private function makeReport(User $owner): Report
    {
        return Report::create([
            'user_id' => $owner->id,
            'facility_id' => $this->makeFacility()->id,
            'category' => 'Kerusakan',
            'description' => 'AC tidak dingin',
            'status' => 'NEW',
        ]);
    }

    public function test_user_can_store_report_with_own_id(): void
    {
        $user = $this->makeUser('USER');
        $facility = $this->makeFacility();
        $other = $this->makeUser('USER');

        $response = $this->actingAs($user)->post(route('reports.store'), [
            'user_id' => $other->id, // spoof: harus diabaikan
            'facility_id' => $facility->id,
            'category' => 'Kelistrikan',
            'description' => 'Lampu mati',
        ]);

        $response->assertRedirect(route('reports.index'));
        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'category' => 'Kelistrikan',
            'status' => 'NEW',
        ]);
        $this->assertDatabaseMissing('reports', ['user_id' => $other->id]);
    }

    public function test_user_cannot_see_other_user_report(): void
    {
        $user = $this->makeUser('USER');
        $other = $this->makeUser('USER');
        $report = $this->makeReport($other);

        // index hanya milik sendiri
        $index = $this->actingAs($user)->getJson(route('reports.index'));
        $index->assertOk();
        $index->assertJsonMissing(['id' => $report->id]);

        // show milik orang lain ditolak
        $this->actingAs($user)->getJson(route('reports.show', $report))->assertForbidden();
    }

    public function test_staff_can_list_and_update_report(): void
    {
        $staff = $this->makeUser('STAFF');
        $owner = $this->makeUser('USER');
        $report = $this->makeReport($owner);

        $this->actingAs($staff)->getJson(route('staff.reports.index'))
            ->assertOk()
            ->assertJsonFragment(['id' => $report->id]);

        $this->actingAs($staff)->getJson(route('staff.reports.show', $report))->assertOk();

        $response = $this->actingAs($staff)->patch(route('staff.reports.update', $report), [
            'status' => 'PROCESSING',
            'resolution_note' => 'Teknisi dijadwalkan.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('PROCESSING', $report->refresh()->status);
        $this->assertEquals('Teknisi dijadwalkan.', $report->refresh()->resolution_note);
    }

    public function test_guest_and_wrong_role_rejected(): void
    {
        $user = $this->makeUser('USER');
        $report = $this->makeReport($user);

        // guest diarahkan ke login
        $this->get(route('reports.index'))->assertRedirect(route('login'));
        $this->get(route('staff.reports.index'))->assertRedirect(route('login'));

        // USER tidak boleh akses area STAFF
        $this->actingAs($user)->get(route('staff.reports.index'))->assertForbidden();

        // STAFF tidak boleh akses area USER
        $staff = $this->makeUser('STAFF');
        $this->actingAs($staff)->post(route('reports.store'), [
            'facility_id' => $report->facility_id,
            'category' => 'X',
            'description' => 'Y',
        ])->assertForbidden();

        // ADMIN tidak diberi akses STAFF
        $admin = $this->makeUser('ADMIN');
        $this->actingAs($admin)->get(route('staff.reports.index'))->assertForbidden();
    }
}
