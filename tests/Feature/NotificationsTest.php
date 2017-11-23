<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->withoutExceptionHandling();

        $thread = \create('App\Thread')->subscribe();

        $thread->addReply([
            'user_id' => \create('App\User')->id,
            'body' => 'test'
        ]);

        $this->assertCount(1, \auth()->user()->unreadNotifications);

        $notification = auth()->user()->unreadNotifications->first()->id;

        $this->delete('/profiles/' . auth()->user()->name . "/notifications/{$notification}");

        $this->assertCount(0, \auth()->user()->fresh()->unreadNotifications);
    }

    /** @test */
    public function a_notification_is_sent_when_a_thread_had_a_new_reply_that_not_by_myself()
    {
        $thread = \create('App\Thread')->subscribe();

        $this->assertCount(0, \auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'test'
        ]);

        $this->assertCount(0, \auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => \create('App\User')->id,
            'body' => 'test'
        ]);

        $this->assertCount(1, \auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_all_unread_notifications()
    {
        \create(DatabaseNotification::class);

        $notification = auth()->user()->unreadNotifications->first()->id;

        $response = $this->getJson('/profiles/' . auth()->user()->name . '/notifications')->json();

        $this->assertCount(1, \auth()->user()->fresh()->unreadNotifications);
    }
}
