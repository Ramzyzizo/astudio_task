<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttributeTest extends TestCase
{
    use DatabaseTransactions;
        protected $user;
    protected $token;

    // 🔹 This method runs before every test
    protected function setUp(): void
    {
        parent::setUp();
        $this->user  = User::factory()->create();
        $this->token = $this->user->createToken('TestToken')->accessToken;
    }

    public function test_store_true()
    {
        $data=[
            'name'=>'color',
            'type'=> 'select'
        ];
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->postJson('/api/attributes', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('attributes', ['name' => 'color']);
    }
    public function test_update_true()
    {
        $attribute = Attribute::create([
            'name' => 'color',
            'type' => 'select'
        ]);

        $updatedData = [
            'name' => 'colorUpdate',
            'type' => 'select'
        ];
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->putJson("/api/attributes/{$attribute->id}", $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('attributes', ['name' => 'colorUpdate']);
    }
    public function test_delete_true()
    {
        $attribute = Attribute::create([
            'name' => 'deletecolor',
            'type' => 'select'
        ]);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->deleteJson("/api/attributes/{$attribute->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('attributes', ['name' => 'deletecolor']);
    }

}
