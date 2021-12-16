<?php

namespace Tests\Feature\School;

use App\Models\Agenda;
use App\Models\Meeting;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\Assert;
use Tests\Setup\RefreshDatabase;
use Tests\TestCase;

class SchoolTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_meeting_index_required_authenication()
    {
        $response = $this->get(route('dashboard.meetings.index'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
    }

    public function test_meeting_index_can_be_viewed_after_login()
    {
        $this->signInAdmin();
        $response = $this->get(route('dashboard.meetings.index'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }

    public function test_meeting_create_page_can_be_accessed_by_admin()
    {
        $this->signInAdmin();
        $response = $this->get(route('dashboard.meetings.create'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }

    public function test_meeting_create_page_can_be_not_accessed_by_teacher()
    {
        $this->signInTeacher();
        $response = $this->get(route('dashboard.meetings.create'));
        $response->assertStatus(403);
    }

    public function test_meeting_edit_page_can_be_accessed_by_admin()
    {
        $this->signInAdmin();
        $meeting = Meeting::factory()->create();
        $response = $this->get(route('dashboard.meetings.edit', $meeting->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }

    public function test_meeting_edit_page_can_not_be_accessed_by_teacher()
    {
        $this->signInTeacher();
        $meeting = Meeting::factory()->create();
        $response = $this->get(route('dashboard.meetings.edit', $meeting->id));
        $response->assertStatus(403);
    }

    public function test_meeting_show_page_can_be_accessed_by_admin()
    {
        $this->signInAdmin();
        $meeting = Meeting::factory()->create();
        $response = $this->get(route('dashboard.meetings.show', $meeting->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertSee($meeting->title);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/meetings/'.$meeting->id, $pageUrl);
    }

    public function test_meeting_show_page_can_be_accessed_by_teacher()
    {
        $this->signInTeacher();
        $meeting = Meeting::factory()->create();
        $response = $this->get(route('dashboard.meetings.show', $meeting->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertSee($meeting->title);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/meetings/'.$meeting->id, $pageUrl);
    }

    public function test_meeting_can_be_created()
    {
        $this->signInAdmin();
        $meeting = Meeting::factory()->make();
        $agendas = Agenda::factory()->count(2)->make(['id' => null])->toArray();
        $response = $this->post(route('dashboard.meetings.store'), [
            'school_id' => $meeting->school_id,
            'title' => $meeting->title,
            'date' => Carbon::parse($meeting->date)->format('Y-m-d'),
            'time' => Carbon::parse($meeting->date)->format('H.i'),
            'location' => $meeting->location,
            'attendee' => $meeting->attendee,
            'agendas' => $agendas,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('meetings', [
            'school_id' => $meeting->school_id,
            'title' => $meeting->title,
            'location' => $meeting->location,
            'attendee' => json_encode($meeting->attendee),
        ]);
        $this->assertDatabaseHas('agendas', [
            'topic' => $agendas[0]['topic'],
            'detail' => $agendas[0]['detail'],
        ]);

    }

    public function test_school_can_be_updated()
    {
        $this->signInAdmin();
        $meeting = Meeting::factory()->create();
        Agenda::factory()->count(2)->create();
        $newAgendas = Agenda::factory()->count(2)->make(['id' => null])->toArray();
        $newAttendee = ['attendee1', 'attendee2'];
        $response = $this->put(route('dashboard.meetings.update', $meeting->id), [
            'school_id' => $meeting->school_id,
            'title' => 'test title',
            'date' => Carbon::parse($meeting->date)->format('Y-m-d'),
            'time' => Carbon::parse($meeting->date)->format('H.i'),
            'location' => 'test location',
            'attendee' => $newAttendee,
            'agendas' => $newAgendas,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('meetings', [
            'school_id' => $meeting->school_id,
            'title' => 'test title',
            'location' => 'test location',
            'attendee' => json_encode($newAttendee),
        ]);
        $this->assertDatabaseHas('agendas', [
            'topic' => $newAgendas[0]['topic'],
            'detail' => $newAgendas[0]['detail'],
        ]);
        $this->assertEquals(2, Agenda::count());
    }

    public function test_meeting_can_be_deleted()
    {
        $this->signInAdmin();
        $meeting = Meeting::factory()->create();
        Agenda::factory()->count(2)->create();
        $this->delete(route('dashboard.meetings.destroy', $meeting->id));
        $this->assertEquals(0, Meeting::count());
        $this->assertEquals(0, Agenda::count());
    }

}
