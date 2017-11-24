<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->json('POST', 'api/users/1/avatar')

            ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        // $this->withoutExceptionHandling();
        $this->signIn();
        $this->json('POST', 'api/users/' . auth()->user()->name . '/avatar', [
            'avatar' => 'not-an-img'
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('POST', 'api/users/' . auth()->user()->name . '/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('avatars/' . $file->hashName()), auth()->user()->avatar_path);
        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
