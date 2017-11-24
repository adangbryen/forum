<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_fetch_their_last_reply()
    {
        $user = create('App\User');

        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function a_user_can_show_their_avatar_path()
    {
        $avatar_path = 'avatars/me.jpg';

        $user = create('App\User');
        $this->assertEquals($user->avatar(), 'images/default.jpg');

        $user->avatar_path = $avatar_path;
        $this->assertEquals($user->avatar(), $avatar_path);
    }
}
