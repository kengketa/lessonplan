<?php

namespace Tests\Feature\School;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\Assert;
use Tests\Setup\RefreshDatabase;
use Tests\TestCase;

class SchoolTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_school_index_required_authenication()
    {
        $response = $this->get(route('dashboard.schools.index'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
    }

    public function test_school_index_can_be_viewed_after_login()
    {
        $this->signInAdmin();
        $response = $this->get(route('dashboard.schools.index'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }

    public function test_school_create_page_can_be_accessed()
    {
        $this->signInAdmin();
        $response = $this->get(route('dashboard.schools.create'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }

    public function test_school_edit_page_can_be_accessed()
    {
        $this->signInAdmin();
        $school = School::factory()->create();
        $response = $this->get(route('dashboard.schools.edit', $school->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }

    public function test_school_show_page_can_be_accessed()
    {
        $this->signInAdmin();
        $school = School::factory()->create();
        $response = $this->get(route('dashboard.schools.show', $school->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/schools/'.$school->id, $pageUrl);
    }

    public function test_school_can_be_created()
    {
        $this->signInAdmin();
        $school = School::factory()->make();
        $response = $this->post(route('dashboard.schools.store'), [
            'name' => $school->name,
            'address' => $school->address,
            'subjects' => $school->subjects,
            'lat' => $school->lat,
            'lng' => $school->lng,
            'radius' => $school->radius
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('schools', [
            'name' => $school->name,
            'address' => $school->address,
            'subjects' => json_encode($school->subjects),
            'lat' => $school->lat,
            'lng' => $school->lng,
            'radius' => $school->radius
        ]);
    }

    public function test_school_can_be_updated()
    {
        $this->signInAdmin();
        $oldSchool = School::factory()->create();
        $newSchool = School::factory()->make();
        $response = $this->put(route('dashboard.schools.update', $oldSchool->id), [
            'name' => $newSchool->name,
            'address' => $newSchool->address,
            'subjects' => $newSchool->subjects,
            'lat' => $newSchool->lat,
            'lng' => $newSchool->lng,
            'radius' => $newSchool->radius
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('schools', [
            'name' => $newSchool->name,
            'address' => $newSchool->address,
            'subjects' => json_encode($newSchool->subjects),
            'lat' => $newSchool->lat,
            'lng' => $newSchool->lng,
            'radius' => $newSchool->radius
        ]);
    }

    public function test_school_can_be_deleted()
    {
        $this->signInAdmin();
        $school = School::factory()->create();
        $this->delete(route('dashboard.schools.destroy', $school->id));
        $this->assertEquals(0, School::count());
    }

}
