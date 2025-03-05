<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TimeSheetTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected $token;
    protected $project;

    // 🔹 This method runs before every test
    protected function setUp(): void
    {
        parent::setUp();
        $this->user  = User::factory()->create();
        $this->project = Project::create([
            'name' => fake()->name(),
            'description' => 'project1 description',
            'status' => 1
        ]);
        $this->token = $this->user->createToken('TestToken')->accessToken;
    }

    public function test_store_true()
    {
        $data = [
            'task_name' => 'first task',
            'project_id' => $this->project->id
        ];
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->postJson('/api/timesheets', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('timesheets', ['task_name' => 'first task']);
    }
    public function test_update_true()
    {
        $proejct = Project::create([
            'name' => fake()->name(),
            'description' => 'project1 description',
            'status' => 1
        ]);
        $attribute = Timesheet::create([
            'task_name' => 'color',
            'project_id' => $this->project->id,
            'user_id' => $this->user->id
        ]);
        $updatedData = [
            'task_name' => 'Task Update'
        ];
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->putJson("/api/timesheets/{$attribute->id}", $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('timesheets', ['task_name' => 'Task Update']);
    }
    public function test_delete_true()
    {
        $attribute = Timesheet::create([
            'task_name' => 'color',
            'project_id' => $this->project->id,
            'user_id' => $this->user->id
        ]);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->deleteJson("/api/timesheets/{$attribute->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('timesheets', ['task_name' => 'color']);
    }
}
