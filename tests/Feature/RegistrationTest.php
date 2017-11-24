<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use App\User;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirm_email_is_sent_when_registration()
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'test',
            'email' => 'testf@testf.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function it_can_fully_confirm_their_email_addresses()
    {
        $this->post('/register', [
            'name' => 'test',
            'email' => 'test3@test.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $user = User::whereName('test')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }
}
