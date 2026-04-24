<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Modules\Management\VocabularyManagement\Vocabulary\Models\Model as Vocabulary;
use Modules\Management\VocabularyManagement\Vocabulary\Models\UserRevisionVocabulary;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class RevisionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $vocabulary;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
        
        // Create a test vocabulary
        $this->vocabulary = Vocabulary::factory()->create();
    }

    /** @test */
    public function user_can_add_vocabulary_to_revision_list()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/vocabularies/add-to-revision', [
            'vocabulary_id' => $this->vocabulary->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Vocabulary added to revision list successfully'
                ]);

        // Verify the vocabulary is in the revision list
        $this->assertDatabaseHas('user_revision_vocabularies', [
            'user_id' => $this->user->id,
            'vocabulary_id' => $this->vocabulary->id
        ]);
    }

    /** @test */
    public function user_cannot_add_same_vocabulary_twice()
    {
        Sanctum::actingAs($this->user);

        // Add vocabulary first time
        UserRevisionVocabulary::create([
            'user_id' => $this->user->id,
            'vocabulary_id' => $this->vocabulary->id
        ]);

        // Try to add again
        $response = $this->postJson('/api/vocabularies/add-to-revision', [
            'vocabulary_id' => $this->vocabulary->id
        ]);

        $response->assertStatus(409)
                ->assertJson([
                    'status' => 'duplicate',
                    'message' => 'This vocabulary is already in your revision list'
                ]);
    }

    /** @test */
    public function user_can_remove_vocabulary_from_revision_list()
    {
        Sanctum::actingAs($this->user);

        // Add vocabulary to revision list first
        UserRevisionVocabulary::create([
            'user_id' => $this->user->id,
            'vocabulary_id' => $this->vocabulary->id
        ]);

        $response = $this->postJson('/api/vocabularies/remove-from-revision', [
            'vocabulary_id' => $this->vocabulary->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Vocabulary removed from revision list successfully'
                ]);

        // Verify the vocabulary is removed from the revision list
        $this->assertDatabaseMissing('user_revision_vocabularies', [
            'user_id' => $this->user->id,
            'vocabulary_id' => $this->vocabulary->id
        ]);
    }

    /** @test */
    public function user_cannot_remove_vocabulary_not_in_revision_list()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/vocabularies/remove-from-revision', [
            'vocabulary_id' => $this->vocabulary->id
        ]);

        $response->assertStatus(404)
                ->assertJson([
                    'status' => 'not_found',
                    'message' => 'This vocabulary is not in your revision list'
                ]);
    }

    /** @test */
    public function add_to_revision_requires_valid_vocabulary_id()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/vocabularies/add-to-revision', [
            'vocabulary_id' => 99999 // Non-existent ID
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    'status' => 'validation_error'
                ]);
    }

    /** @test */
    public function remove_from_revision_requires_valid_vocabulary_id()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/vocabularies/remove-from-revision', [
            'vocabulary_id' => 99999 // Non-existent ID
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    'status' => 'validation_error'
                ]);
    }

    /** @test */
    public function unauthorized_user_cannot_manage_revision_list()
    {
        // Don't authenticate the user

        $response = $this->postJson('/api/vocabularies/add-to-revision', [
            'vocabulary_id' => $this->vocabulary->id
        ]);

        $response->assertStatus(401);

        $response = $this->postJson('/api/vocabularies/remove-from-revision', [
            'vocabulary_id' => $this->vocabulary->id
        ]);

        $response->assertStatus(401);
    }
}