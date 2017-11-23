<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_should_be_notified()
    {
        $this->withoutExceptionHandling();
        $john = create('App\User', ['name' => 'John']);
        $this->signIn($john);
        $jane = create('App\User', ['name' => 'Jane']);
        $thread = \create('App\Thread');

        $reply = \create('App\Reply', [
            'body' => '@Jane haha @xx'
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray());
        // $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }
}
