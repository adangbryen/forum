<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->signIn();

        // $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_unlock_any_threads()
    {
        $this->signIn(create('App\User', ['name' => 'admin']));
        $thread = create('App\Thread');
        $this->post(route('locked-threads.store', $thread));
        $this->assertTrue($thread->fresh()->locked);
        $this->delete(route('locked-threads.destroy', $thread));
        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_lock_any_threads()
    {
        $this->signIn(create('App\User', ['name' => 'admin']));
        $thread = create('App\Thread');
        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function locked_thread_may_not_reply()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies/', [
            'body' => 'foo',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
