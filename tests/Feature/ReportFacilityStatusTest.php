<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportFacilityStatusTest extends TestCase
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

    private function makeOwner(): User
    {
        return User::create([
            'name' => 'User Test',
            'email' => 'user_'.uniqid().'@test.com',
            'password' => 'password123',
            'role' => 'USER',
            'status' => 'ACTIVE',
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

    private function makeReport(Facility $facility, string $status = 'NEW'): Report
    {
        return Report::create([
            'user_id' => $this->makeOwner()->id,
            'facility_id' => $facility->id,
            'category' => 'Kerusakan',
            'description' => 'AC tidak dingin',
            'status' => $status,
        ]);
    }

    public function test_processing_sets_facility_maintenance(): void
    {
        $staff = $this->makeStaff();
        $facility = $this->makeFacility('AVAILABLE');
        $report = $this->makeReport($facility);

        $this->actingAs($staff)->patch(route('staff.reports.update', $report), [
            'status' => 'PROCESSING',
        ])->assertSessionHas('success');

        $this->assertEquals('PROCESSING', $report->refresh()->status);
        $this->assertEquals('MAINTENANCE', $facility->refresh()->status);
    }

    public function test_completed_returns_facility_to_available(): void
    {
        $staff = $this->makeStaff();
        $facility = $this->makeFacility('MAINTENANCE');
        $report = $this->makeReport($facility, 'PROCESSING');

        $this->actingAs($staff)->patch(route('staff.reports.update', $report), [
            'status' => 'COMPLETED',
            'resolution_note' => 'AC sudah diperbaiki.',
        ])->assertSessionHas('success');

        $this->assertEquals('COMPLETED', $report->refresh()->status);
        $this->assertEquals('AVAILABLE', $facility->refresh()->status);
    }

    public function test_completed_keeps_maintenance_when_other_processing_exists(): void
    {
        $staff = $this->makeStaff();
        $facility = $this->makeFacility('MAINTENANCE');
        $report = $this->makeReport($facility, 'PROCESSING');
        $this->makeReport($facility, 'PROCESSING'); // laporan lain masih ditangani

        $this->actingAs($staff)->patch(route('staff.reports.update', $report), [
            'status' => 'COMPLETED',
            'resolution_note' => 'Satu titik selesai.',
        ])->assertSessionHas('success');

        $this->assertEquals('COMPLETED', $report->refresh()->status);
        $this->assertEquals('MAINTENANCE', $facility->refresh()->status);
    }

    public function test_rejected_does_not_create_maintenance(): void
    {
        $staff = $this->makeStaff();
        $facility = $this->makeFacility('AVAILABLE');
        $report = $this->makeReport($facility);

        $this->actingAs($staff)->patch(route('staff.reports.update', $report), [
            'status' => 'REJECTED',
            'resolution_note' => 'Bukan kerusakan.',
        ])->assertSessionHas('success');

        $this->assertEquals('REJECTED', $report->refresh()->status);
        $this->assertEquals('AVAILABLE', $facility->refresh()->status);
    }

    public function test_completed_and_rejected_require_resolution_note(): void
    {
        $staff = $this->makeStaff();

        foreach (['COMPLETED', 'REJECTED'] as $status) {
            $facility = $this->makeFacility();
            $report = $this->makeReport($facility, 'PROCESSING');

            $this->actingAs($staff)->patch(route('staff.reports.update', $report), [
                'status' => $status,
            ])->assertSessionHasErrors('resolution_note');

            $this->assertEquals('PROCESSING', $report->refresh()->status);
        }
    }
}
