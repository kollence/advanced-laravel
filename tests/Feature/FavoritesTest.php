<?php

namespace Tests\Feature;

use App\Models\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling();
        $this->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }
    public function test_auth_user_can_favorite_any_reply()
    {
        $this->signIn();
        // create one reply
        
        $reply = factoryCreate(\App\Models\Reply::class);
        $this->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    public function test_auth_user_can_unfavorite_any_reply()
    {
        $this->signIn();
        // create one reply
        $reply = factoryCreate(\App\Models\Reply::class);
        
        $reply->favorite();

        $this->delete('/replies/'.$reply->id.'/favorites');

        $this->assertCount(0, $reply->favorites);
    }


    public function test_auth_user_can_favorite_reply_only_once()
    {
        $this->signIn();
        // create one reply
        $reply = factoryCreate(\App\Models\Reply::class);
        $this->post('/replies/'.$reply->id.'/favorites');
        $this->post('/replies/'.$reply->id.'/favorites');
        // dd(\App\Models\Favorite::all());
        $this->assertCount(1, $reply->favorites);
    }
}
