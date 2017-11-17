<?php

namespace Test\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    function a_user_can_browse_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
}

    /** @test */
    function a_user_can_read_a_single_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);

    }
    
    /** @test */
    function a_user_can_read_replies_that_associated_with_a_thread()
    {
        $reply =create('App\Reply', ['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }
    
    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadsInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadsNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadsInChannel->title)
            ->assertDontSee($threadsNotInChannel->title);
    }
    
    /** @test */
    function a_user_can_filter_threads_by_a_username()
    {
        $this->singIn(create('App\User', ['name' => 'foo']));

        $threadByFoo = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByFoo = create('App\Thread');

        $this->get('/threads?by=foo')
            ->assertSee($threadByFoo->title)
            ->assertDontSee($threadNotByFoo->title);
    }
    
    /** @test */
    function a_user_can_filter_threads_by_populority()
    {
        $threadsOne = create('App\Thread');
        $threadsTwo = create('App\Thread');
        $threadsThree = create('App\Thread');

        create('App\Reply', ['thread_id' => $threadsThree->id], 3);
        create('App\Reply', ['thread_id' => $threadsTwo->id], 2);
        create('App\Reply', ['thread_id' => $threadsOne->id]);

        $response = $this->getJson('/threads?popular=1')->json();
//        $response = $this->getJson('/threads')->json();

        $this->assertEquals([3,2,1,0], array_column($response, 'replies_count'));
    }
}