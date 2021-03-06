<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirm_email_is_sent_when_registration()
    {
        $this->withExceptionHandling();
        Mail::fake();

        $this->post('/register', [
            'name' => 'test',
            'email' => 'testf@testf.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
        $this->assertTrue(Mail::hasQueued(PleaseConfirmYourEmail::class));
    }

    /** @test */
    public function it_can_fully_confirm_their_email_addresses()
    {
        Mail::fake();

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
        $this->assertNull($user->fresh()->confirmation_token);

        $response->assertRedirect('/threads');
    }

    /** @test */
    public function confirming_an_invalid_token()
    {
        $this->withoutExceptionHandling();

        $this->expectException(ModelNotFoundException::class);
        $this->get(route('register.confirm', ['token' => 'invalid']));
    }
}
