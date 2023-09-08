<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_guest_cant_add_reply()
    {
        $this->post('/threads/some-channel/1/replies', [])->assertRedirect('/login');
    }

    public function test_auth_can_add_reply()
    { 
        $this->signIn();

        $thread = factoryCreate(Thread::class);
        $reply = factoryMake(Reply::class);
        $this->post($thread->path().'/replies', $reply->toArray());
        // $this->get($thread->path())->assertSee($reply->body);
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    public function test_a_reply_requiers_a_body()
    {
        $this->signIn();
        $thread = factoryCreate(Thread::class);
        $reply = factoryMake(Reply::class, ['body' => null]);
        $this->post($thread->path().'/replies', $reply->toArray())
        ->assertSessionHasErrors('body');
    }

    public function test_guest_cant_delete_reply()
    {
        $this->withExceptionHandling();
        $reply = factoryCreate(Reply::class);
        $this->delete("/replies/{$reply->id}")->assertRedirect('/login');
    }

    public function test_auth_cant_delete_reply_that_its_not_its_own()
    {
        $this->signIn();
        $reply = factoryCreate(Reply::class);
        $this->delete("/replies/{$reply->id}")->assertStatus(403); // 403 Forbidden
    }

    public function test_auth_can_delete_only_its_own_reply()
    {
        $this->signIn();
        $reply = factoryCreate(Reply::class, ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    public function test_guest_cant_update_reply()
    {
        $this->withExceptionHandling();
        $reply = factoryCreate(Reply::class);
        $this->patch("/replies/{$reply->id}")->assertRedirect('/login');
    }

    public function test_auth_cant_update_reply_that_its_not_its_own()
    {
        $this->signIn();
        $reply = factoryCreate(Reply::class);
        $this->patch("/replies/{$reply->id}")->assertStatus(403); // 403 Forbidden
    }

    public function test_auth_can_update_only_its_own_reply()
    {
        $this->signIn();
        $reply = factoryCreate(Reply::class, ['user_id' => auth()->id()]);
        $this->patch("/replies/{$reply->id}", ['body' => 'foobar'])->assertStatus(302);
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'foobar']); // 302 Found
    }

    public function test_reply_that_contain_spam_can_not_be_created()
    {   
        $this->signIn();
        $thread = factoryCreate(Thread::class);
        $reply = factoryMake(Reply::class, ['body' => 'Spam text']);

        $this->post($thread->path().'/replies', $reply->toArray())
        ->assertSessionHasErrors('body');
    }
}
