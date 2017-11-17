<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->post('/threads/channel/1/replies', [])
            ->assertRedirect('/login');
    }
    
    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->singIn();
        $thread = create('App\Thread');
        $reply= make('App\Reply');

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }
    
    /** @test */
    function a_reply_need_a_body()
    {
        $this->singIn();
        $thread = create('App\Thread');
        $reply= make('App\Reply', ['body' => null]);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');

    }

    /** @test */
    function unauthencated_user_cannot_delete_reply()
    {
        $reply = create('App\Reply');

        $this->delete('/replies/'. $reply->id)
            ->assertRedirect('/login');

        $this->singIn();

        $this->delete('/replies/'. $reply->id)
            ->assertStatus(403);

    }
    
    /** @test */
    function authenticated_user_can_delete_reply()
    {
        $this->singIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete('/replies/'. $reply->id);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

    }


    /** @test */
    function unauthenticated_user_can_update_reply()
    {
        $reply = create('App\Reply');

        $this->patch('/replies/'. $reply->id)
            ->assertRedirect('/login');

        $this->singIn();

        $this->patch('/replies/'. $reply->id)
            ->assertStatus(403);
    }
    /** @test */
    function authenticated_user_can_update_reply()
    {
        $this->singIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->patch('/replies/'. $reply->id, ['body' => 'test']);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'test']);
    }

}
