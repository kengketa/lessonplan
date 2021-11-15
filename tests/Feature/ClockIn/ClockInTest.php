<?php

namespace Tests\Feature\ClockIn;

use App\Models\ClockIn;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\Assert;
use Tests\Setup\RefreshDatabase;
use Tests\TestCase;

class ClockInTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_clock_in_index_required_authenication()
    {
        $response = $this->get(route('dashboard.clock_ins.index'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
    }

    public function test_clock_in_index_can_be_viewed_after_login()
    {
        $this->signInAdmin();
        $response = $this->get(route('dashboard.clock_ins.index'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }

    public function test_teacher_can_clock_in_and_out()
    {
        $teacher = $this->signInTeacher();
        $school = School::factory()->create();
        $school->teachers()->attach($teacher);
        $this->get(route('dashboard'));
        $response = $this->post(route('dashboard.clock_ins.in'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('clock_ins', [
            'teacher_id' => $teacher->id,
            'school_id' => $school->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'clock_out' => null
        ]);
        $this->assertNotNull(ClockIn::first()->clock_in);

        $response = $this->post(route('dashboard.clock_ins.out'));
        $this->assertNotNull(ClockIn::first()->clock_out);
    }

    public function test_teacher_can_view_his_time_sheet()
    {
        $teacher = $this->signInTeacher();
        $school = School::factory()->create();
        $school->teachers()->attach($teacher);
        $response = $this->get(route('dashboard.my_time_sheet'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/my-time-sheets', $pageUrl);
    }

}
