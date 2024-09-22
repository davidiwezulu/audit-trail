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
use App\Models\User;

class RelationshipsTest extends TestCase
{
    use WithFaker;

    /**
     * Test that the AuditTrail morphs to the correct auditable model.
     *
     * @return void
     */
    public function testAuditTrailMorphsToModel()
    {
        // Arrange
        $post = Post::create(['title' => $this->faker->sentence]);

        // Act
        $auditTrail = AuditTrail::first();

        // Assert
        $this->assertInstanceOf(Post::class, $auditTrail->auditable);
        $this->assertTrue($auditTrail->auditable->is($post));
    }

    /**
     * Test that the AuditTrail belongs to the authenticated user.
     *
     * @return void
     */
    public function testAuditTrailBelongsToUser()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act
        $post = Post::create(['title' => $this->faker->sentence]);
        $auditTrail = AuditTrail::first();

        // Assert
        $this->assertEquals($user->id, $auditTrail->user_id);
        $this->assertInstanceOf(User::class, $auditTrail->user);
        $this->assertTrue($auditTrail->user->is($user));
    }
}

