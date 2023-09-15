<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGusetCantAddAvatarImage()
    {
        $this->json('POST', 'api/users/1/avatar')->assertStatus(401);
    }

    public function testAuthCantAddInvalidAvatarImage()
    {
        $this->signIn();

        $this->json('POST', 'api/users/'.auth()->id().'/avatar', [
            'avatar_img' => 'invalid-image'
        ])->assertStatus(422);
    }

    public function testAuthCanAddValidAvatarImage()
    {
        $this->signIn();

        Storage::fake('public');
        //UploadFile class to fake one
        $file = UploadedFile::fake()->image('avatar.jpg', 30,30);

        $this->json('POST', 'api/users/'.auth()->id().'/avatar', [
            'avatar_img' => $file
        ]);
        // Assert that path is saved to users table column avatar_img
        $this->assertEquals('avatars/'.$file->hashName(), auth()->user()->avatar_img);
        // Assert the file was stored...
        Storage::disk('public')->assertExists('avatars/'. $file->hashName());
    }

    public function testAuthCanUseDefaultImageIfTheyDontHaveOne()
    {
        $this->signIn();
        $user = auth()->user();

        $this->assertEquals('storage/avatars/default.png', $user->avatar());

        $user->avatar_img = 'avatars/myAddedPhoto.jpg';

        $this->assertEquals('storage/avatars/myAddedPhoto.jpg', $user->avatar());

    }
}
