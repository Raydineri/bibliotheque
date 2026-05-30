<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\ChatbotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class ChatbotFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'member']);
    }

    public function test_chatbot_endpoint_returns_valid_structure(): void
    {
        // Mock du service pour ne pas appeler Gemini
        $this->mock(ChatbotService::class, function ($mock) {
            $mock->shouldReceive('ask')
                ->once()
                ->andReturn('Voici les livres disponibles : ...');
        });

        $user = User::factory()->create();
        $user->assignRole('member');

        $this->actingAs($user)
            ->postJson('/api/chatbot', ['message' => 'Livres disponibles ?'])
            ->assertStatus(200)
            ->assertJsonStructure(['reply']);
    }

    public function test_chatbot_requires_authentication(): void
    {
        $this->postJson('/api/chatbot', ['message' => 'test'])
            ->assertStatus(401);
    }

    public function test_chatbot_validates_empty_message(): void
    {
        $user = User::factory()->create();
        $user->assignRole('member');

        $this->actingAs($user)
            ->postJson('/api/chatbot', ['message' => ''])
            ->assertStatus(422);
    }
}
