<?php
/**
 * Davidiwezulu/AuditTrail
 *
 * @license MIT
 * @copyright Copyright (c) 2021 David Iwezulu
 */

namespace Davidiwezulu\AuditTrail\Tests;

use Davidiwezulu\AuditTrail\Models\AuditTrail;
use Davidiwezulu\AuditTrail\Tests\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;

class ModelChangesTest extends TestCase
{
    use WithFaker;

    /**
     * Test that creating a model generates an audit trail.
     *
     * @return void
     */
    public function testCreatingModelGeneratesAuditTrail()
    {
        // Act
        $post = Post::create(['title' => $this->faker->sentence]);

        // Assert
        $this->assertDatabaseHas('audit_trails', [
            'auditable_type' => Post::class,
            'auditable_id'   => $post->id,
            'event'          => 'created',
        ]);
    }

    /**
     * Test that updating a model generates an audit trail.
     *
     * @return void
     */
    public function testUpdatingModelGeneratesAuditTrail()
    {
        // Arrange
        $post = Post::create(['title' => $this->faker->sentence]);
        $newTitle = $this->faker->sentence;

        // Act
        $post->update(['title' => $newTitle]);

        // Assert
        $this->assertDatabaseHas('audit_trails', [
            'auditable_type' => Post::class,
            'auditable_id'   => $post->id,
            'event'          => 'updated',
        ]);
    }

    /**
     * Test that deleting a model generates an audit trail.
     *
     * @return void
     */
    public function testDeletingModelGeneratesAuditTrail()
    {
        // Arrange
        $post = Post::create(['title' => $this->faker->sentence]);
        $postId = $post->id;

        // Act
        $post->delete();

        // Assert
        $this->assertDatabaseHas('audit_trails', [
            'auditable_type' => Post::class,
            'auditable_id'   => $postId,
            'event'          => 'deleted',
        ]);
    }
}
