<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = create('App\User');

        $this->get('/profiles/' . $user->name)
            ->assertSee($user->name);
    }

    /** @test */
    public function profiles_display_all_threads_by_the_associated_user()
    {
        $this->signIn();
        $user = auth()->user();
        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->get('/profiles/' . $user->name)
            ->assertSee($thread->title);
    }
}
