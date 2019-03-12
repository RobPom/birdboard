<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;

class ProjectTasks extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        
        $project = factory('App\Project')->create();

        $this->post( $project->path() . '/tasks' , ['body' => 'Test Task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks' , ['body' => 'Test Task']);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();
        
        $project = factory('App\Project')->create();

        $task = $project->addTask('Test Task');

        $this->patch( $task->path(), ['body' => 'changed'])
            ->assertStatus(403);
        
        $this->assertDatabaseMissing('tasks' , ['body' => 'changed']);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        $project =  auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $this->post( $project->path() . '/tasks' , ['body' => 'Test Task']);
        
        $this->get($project->path())
            ->assertSee('Test Task');
    }
    /** @test */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project =  auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $task = $project->addTask('test task');

        $this->patch($task->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks' , [
            'body' => 'changed',
            'completed' => true
        ]);
    }
    
    /** @test */
    public function a_tasks_requires_a_body()
    {
        $this->signIn();

        $project =  auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $attributes = factory('App\Task')->raw(['body' => '']);
        $this->post($project->path() . '/tasks' , $attributes )->assertSessionhasErrors('body');
    }

}
