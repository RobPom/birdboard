<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creating_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created' , $project->activity->first()->description);
    }


    /** @test */
    function updating_a_project()
    {
        $project = ProjectFactory::create();
        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->description);
    }

    /** @test */
    function creating_a_new_task()
    {
        $project = ProjectFactory::create();
        $project->addTask('some task');
        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_task', $project->activity->last()->description);
    }

    /** @test */
    function completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)
            ->patch($project->tasks()->first()->path() , [
                'body' => 'foobar',
                'completed' => true
            ]);
     
        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed_task', $project->activity->last()->description);
    }

    /** @test */
    function incompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)
            ->patch($project->tasks()->first()->path() , [
                'body' => 'foobar',
                'completed' => true
            ]);
     
        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks()->first()->path() , [
            'body' => 'foobar',
            'completed' => false
        ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);
        
        $this->assertEquals('marked_incomplete', $project->activity->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $project->tasks->first()->delete();
        $this->assertCount(3, $project->activity);

    }
}
