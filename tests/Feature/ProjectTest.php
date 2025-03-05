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

class ProjectTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected $token;
    protected $attribute;

    // 🔹 This method runs before every test
    protected function setUp(): void
    {
        parent::setUp();
        $this->user  = User::factory()->create();
        $this->attribute = Attribute::create([
            'name' =>  fake()->name(),
            'type' => 'select'
        ]);
        $this->token = $this->user->createToken('TestToken')->accessToken;
    }

    public function test_store_true()
    {
        $data = [
            'name' =>  fake()->name(),
            'status' =>  1,
            'attributes' => [
                ['attribute_id' => $this->attribute->id, 'value' => 'first value', 'start_date' => '2025-03-20', 'end_date' => '2025-05-20']
            ]
        ];
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->postJson('/api/projects', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', ['name' => $data['name'], 'status' => $data['status']]);
        $this->assertDatabaseHas('attribute_values', [
            'attribute_id' => $this->attribute->id,
            'value' => 'first value',
            'start_date' => '2025-03-20',
            'end_date' => '2025-05-20'
        ]);
    }

    public function test_update_true()
    {
        $project = $this->user->projects()->create([
            'name' => fake()->name(),
            'status' => 1
        ]);
        $attributesData = [
            $this->attribute->id => ['value' => '2 Kilo', 'start_date' => '2025-03-20', 'end_date' => '2025-05-20']
        ];
        $project->attributes()->attach($attributesData);
        $updatedData = [
            'name' => 'Updated Project Name',
            'status' => 0,
            'attributes' => [
                ['attribute_id' => $this->attribute->id, 'value' => '10 Kilo', 'start_date' => '2025-02-15', 'end_date' => '2025-08-30']
            ]
        ];
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->putJson("/api/projects/{$project->id}", $updatedData);
            
        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'name' => 'Updated Project Name',
            'status' => 0
        ]);
        $this->assertDatabaseHas('attribute_values', [
            'attribute_id' => $this->attribute->id,
            'entity_id' => $project->id,
            'value' => '10 Kilo',
            'start_date' => '2025-02-15',
            'end_date' => '2025-08-30'
        ]);
        
    }
    public function test_delete_true()
    {
        $project = $this->user->projects()->create([
            'name' => fake()->name(),
            'status' => 1
        ]);
        $attributesData=[
            $this->attribute->id => ['value' => '2 Kilo', 'start_date' => '2025-03-20', 'end_date' => '2025-05-20']
        ];
        $project->attributes()->attach($attributesData);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('projects', ['name' => $project->name]);
        $this->assertDatabaseMissing('attribute_values', ['entity_id' => $project->id]);
    }
}
