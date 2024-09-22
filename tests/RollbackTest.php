<?php
/**
 * Davidiwezulu/AuditTrail
 *
 * @license MIT
 * @copyright Copyright (c) 2021 David Iwezulu
 */

namespace Davidiwezulu\AuditTrail\Tests;

use Davidiwezulu\AuditTrail\Tests\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;

class RollbackTest extends TestCase
{
    use WithFaker;

    /**
     * Test that a model can be rolled back to a previous version.
     *
     * @return void
     */
    public function testModelRollback()
    {
        // Arrange
        $originalTitle = 'Original Title';
        $updatedTitle = 'Updated Title';

        $post = Post::create(['title' => $originalTitle]);
        $post->update(['title' => $updatedTitle]);

        $auditTrail = $post->auditTrails()->where('event', 'updated')->first();

        // Act
        $post->rollbackTo($auditTrail->id);

        // Assert
        $this->assertEquals($originalTitle, $post->fresh()->title);
    }
}
