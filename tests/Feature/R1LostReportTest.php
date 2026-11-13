<?php

namespace Tests\Feature;

use App\Models\LostReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class R1LostReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_lost_reports(): void
    {
        $this->get(route('lost-reports.index'))->assertRedirect(route('login'));
        $this->post(route('lost-reports.store'), [])->assertRedirect(route('login'));
    }

    public function test_non_student_roles_are_forbidden_from_student_lost_report_routes(): void
    {
        $security = User::factory()->create(['role' => 'security']);

        $this->actingAs($security)
            ->get(route('lost-reports.index'))
            ->assertForbidden();
    }

    public function test_student_can_create_lost_report_with_photo(): void
    {
        Storage::fake('public');
        $student = User::factory()->create(['role' => 'student']);
        $photo = UploadedFile::fake()->image('charger.jpg', 400, 400);

        $response = $this->actingAs($student)->post(route('lost-reports.store'), [
            'item_name' => 'ThinkPad USB-C Charger',
            'category' => 'electronics',
            'description' => '65W USB-C power adapter left near power outlet in lecture hall.',
            'lost_date' => now()->subDay()->toDateString(),
            'location' => 'UB2 Building',
            'photo' => $photo,
        ]);

        $report = LostReport::firstOrFail();

        $response->assertRedirect(route('lost-reports.show', $report));
        $this->assertSame($student->id, $report->user_id);
        $this->assertSame(LostReport::STATUS_SEARCHING, $report->status);
        $this->assertNotNull($report->photo_path);
        Storage::disk('public')->assertExists($report->photo_path);
    }

    public function test_student_can_filter_only_their_own_lost_reports(): void
    {
        $studentA = User::factory()->create(['role' => 'student']);
        $studentB = User::factory()->create(['role' => 'student']);

        LostReport::factory()->create([
            'user_id' => $studentA->id,
            'item_name' => 'Silver Keychain',
            'status' => LostReport::STATUS_SEARCHING,
        ]);
        LostReport::factory()->create([
            'user_id' => $studentA->id,
            'item_name' => 'Red Notebook',
            'status' => LostReport::STATUS_RECOVERED,
        ]);
        LostReport::factory()->create([
            'user_id' => $studentB->id,
            'item_name' => 'Other Student Wallet',
        ]);

        $response = $this->actingAs($studentA)
            ->get(route('lost-reports.index', ['search' => 'Keychain', 'status' => 'searching']));

        $response->assertOk();
        $response->assertSee('Silver Keychain');
        $response->assertDontSee('Red Notebook');
        $response->assertDontSee('Other Student Wallet');
    }

    public function test_student_cannot_view_edit_or_delete_another_students_report(): void
    {
        $owner = User::factory()->create(['role' => 'student']);
        $otherStudent = User::factory()->create(['role' => 'student']);
        $report = LostReport::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherStudent)->get(route('lost-reports.show', $report))->assertForbidden();
        $this->actingAs($otherStudent)->get(route('lost-reports.edit', $report))->assertForbidden();
        $this->actingAs($otherStudent)->put(route('lost-reports.update', $report), [])->assertForbidden();
        $this->actingAs($otherStudent)->delete(route('lost-reports.destroy', $report))->assertForbidden();
    }

    public function test_student_can_update_report_and_transition_status(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $report = LostReport::factory()->create([
            'user_id' => $student->id,
            'status' => LostReport::STATUS_SEARCHING,
        ]);

        $this->actingAs($student)
            ->put(route('lost-reports.update', $report), [
                'item_name' => 'Updated Backpack',
                'category' => 'clothes',
                'description' => 'Updated detailed description of the black canvas backpack.',
                'lost_date' => now()->subDays(3)->toDateString(),
                'location' => 'Auditorium',
                'status' => LostReport::STATUS_MATCHED,
            ])
            ->assertRedirect(route('lost-reports.show', $report));

        $this->assertDatabaseHas('lost_reports', [
            'id' => $report->id,
            'item_name' => 'Updated Backpack',
            'status' => LostReport::STATUS_MATCHED,
        ]);

        $this->actingAs($student)
            ->patch(route('lost-reports.update-status', $report), [
                'status' => LostReport::STATUS_RECOVERED,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('lost_reports', [
            'id' => $report->id,
            'status' => LostReport::STATUS_RECOVERED,
        ]);
    }

    public function test_deleting_lost_report_removes_stored_photo(): void
    {
        Storage::fake('public');
        $student = User::factory()->create(['role' => 'student']);
        $storedPath = UploadedFile::fake()->image('watch.png')->store('lost-reports', 'public');

        $report = LostReport::factory()->create([
            'user_id' => $student->id,
            'photo_path' => $storedPath,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $this->actingAs($student)
            ->delete(route('lost-reports.destroy', $report))
            ->assertRedirect(route('lost-reports.index'));

        $this->assertDatabaseMissing('lost_reports', ['id' => $report->id]);
        Storage::disk('public')->assertMissing($storedPath);
    }
}
