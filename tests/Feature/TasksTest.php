<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testAllTasks()
    {
        $task = factory(Task::class)->create();

        $this->getJson(route('api.tasks'))
            ->assertOk()
            ->assertJsonStructure(['tasks']);
    }

    public function testShowTask()
    {
        $task = factory(Task::class)->create();

        $this->getJson(route('api.tasks.show', ['task' => $task->id]))
            ->assertOk()
            ->assertJsonStructure(['task']);
    }

    public function testCreateTask()
    {
        $this->actingAs(factory(User::class)->create());

        $task = factory(Task::class)->make();

        $this->postJson(route('api.tasks.create', $task->toArray()))
            ->assertCreated()
            ->assertJsonStructure(['task']);
        $this->assertEquals(1, Task::count());
    }

    public function testCreateTaskUnAuthorizedUser()
    {
        $task = factory(Task::class)->make();

        $response = $this->postJson(route('api.tasks.create', $task->toArray()))
            ->assertUnauthorized();
        $this->assertEquals('Unauthenticated.', $response['message']);
    }

    public function testUpdateTask()
    {
        $this->actingAs(factory(User::class)->create());

        $task = factory(Task::class)->create(['user_id' => auth()->user()->id]);
        $task->title = "Updated Title";
        
        $this->putJson('/api/v1/tasks/'.$task->id, $task->toArray())
            ->assertCreated();
        $this->assertDatabaseHas('tasks',['id'=> $task->id , 'title' => 'Updated Title']);
    }

    public function testDeleteTask()
    {
        $this->actingAs(factory(User::class)->create());
        $task = factory(Task::class)->create(['user_id' => auth()->user()->id]);

        $this->deleteJson(route('api.tasks.delete', [ $task ]))
            ->assertOk();
        $this->assertDatabaseMissing('tasks', ['id'=> $task->id]);
    }
}

