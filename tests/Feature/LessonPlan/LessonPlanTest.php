<?php

namespace Tests\Feature\LessonPlan;

use App\Actions\PrepareReportAction;
use App\Models\Grade;
use App\Models\Report;
use App\Models\School;
use App\Models\User;
use http\Env\Request;
use Inertia\Testing\Assert;
use Spatie\Permission\Models\Role;
use Tests\Setup\RefreshDatabase;
use Tests\TestCase;

class LessonPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_lesson_plan_list_page_viewing_required_authenication()
    {
        $school = School::factory()->create();
        $response = $this->get(route('dashboard.schools.show', $school->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
    }

    public function test_only_teacher_in_the_school_can_view_lesson_plans_in_the_school()
    {
        $teacher = $this->signInTeacher();
        $school = School::factory()->create();
        $response = $this->get(route('dashboard.schools.show', $school->id));
        $response->assertStatus(302);

        $school->teachers()->attach($teacher);
        $response = $this->get(route('dashboard.schools.show', $school->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/schools/'.$school->id, $pageUrl);
    }

    public function test_lesson_plan_create_page_can_be_viewed()
    {
        $teacher = $this->signInTeacher();
        $school = School::factory()->create();
        $school->teachers()->attach($teacher);
        $response = $this->get(route('dashboard.reports.create', $school->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/schools/'.$school->id.'/reports/create', $pageUrl);
    }

    public function test_lesson_plan_can_be_created()
    {
        $teacher = $this->signInTeacher();
        $school = School::factory()->create();
        $school->teachers()->attach($teacher);
        $grade = Grade::factory()->create();
        $this->get(route('dashboard.reports.create', $school->id));
        $response = $this->post(route('dashboard.reports.store'), [
            'school_id' => $school->id,
            'subject' => $school->subjects[0]['id'],
            'week_number' => 1,
            'lesson_number' => 1,
            'teaching_materials' => 'test material',
            'activities' => 'test activities',
            'outcome' => null,
            'outstanding_students' => null,
            'need_improvement_students' => null,
            'report' => [
                'for_grades' => [
                    0 => ['id' => $grade->id, 'date' => null]
                ],
                'plans' => [
                    0 => [
                        'type' => 1,
                        'topic' => 'test topic',
                        'vocabs' => ['cat', 'dog', 'cow'],
                        'typing_vocab' => null,
                        'details' => '<p>sdfsdbvnfs</p>'
                    ]
                ]
            ],
        ]);
        $this->assertDatabaseHas('reports', [
            'grade_id' => $grade->id,
            'plans' => '[{"type":1,"topic":"test topic","vocabs":["cat","dog","cow"],"details":"<p>sdfsdbvnfs<\/p>"}]',
        ]);
    }

    public function test_lesson_plan_can_be_updated()
    {
        $teacher = $this->signInTeacher();
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);
        $admin->assignRole(Role::where('name', \App\Models\Role::ROLE_ADMIN)->first());
        $school = School::factory()->create();
        $school->teachers()->attach($teacher);
        $grade = Grade::factory()->create();
        $report = Report::factory()->create();

        $this->get(route('dashboard.reports.edit', $report->id));
        $transformedReport = new PrepareReportAction();
        $reportData = $transformedReport->execute($school, $report);
        $editReport = $reportData;

        $response = $this->put(route('dashboard.reports.update', $report->id), [
            'school_id' => $school->id,
            'subject' => $editReport['subject'],
            'week_number' => 1,
            'lesson_number' => 1,
            'teaching_materials' => 'test material',
            'activities' => 'test activities',
            'outcome' => 'test outcome',
            'outstanding_students' => 'test outstanding students',
            'need_improvement_students' => 'need_improvement_students',
            'report' => [
                'for_grades' => [
                    0 => ['id' => $grade->id, 'date' => null]
                ],
                'plans' => [
                    0 => [
                        'type' => 1,
                        'topic' => 'test topic',
                        'vocabs' => ['cat', 'dog', 'cow'],
                        'typing_vocab' => null,
                        'details' => '<p>test update plan</p>'
                    ]
                ]
            ],
        ]);
        $this->assertDatabaseHas('reports', [
            'grade_id' => $grade->id,
            'plans' => '[{"type":1,"topic":"test topic","vocabs":["cat","dog","cow"],"details":"<p>test update plan<\/p>"}]',
        ]);
    }


}
