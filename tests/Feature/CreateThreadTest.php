<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Channel;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    protected $userWithConfirmedEmail;

    protected function setUp() : void
    {
        parent::setUp();
    }

    public function test_guest_cant_create_thread()
    {   // with Exception Handling
        $this->withExceptionHandling();
        // can't go to page
        $this->get('/threads/create')
        ->assertRedirect('/login');

        $thread = factoryMake(\App\Models\Thread::class);
        // can't create
        $this->post(route('threads.store'), $thread->toArray())
        ->assertRedirect('/login');  
    }

    public function test_auth_user_can_create_thread()
    {
        //authenticate user
        $this->signInConfirmedUser();
        //create thread
        $thread = factoryMake(\App\Models\Thread::class);
        $response = $this->post('/threads', $thread->toArray());
        //redirect to thread page taking from ThreadController redirect
        $redirect = $response->headers->get('location');
        $this->get($redirect)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    public function test_created_thread_has_unique_slug_based_on_id()
    {
        //authenticate user
        $this->signInConfirmedUser();
        //create thread
        $thread = factoryCreate(\App\Models\Thread::class, ['title' => 'Unique Title']);
        $thread2 = factoryCreate(\App\Models\Thread::class, ['title' => 'Unique Title']);
        $this->assertEquals($thread->title, $thread2->title);
        $this->assertNotEquals($thread->slug, $thread2->slug);
        $this->assertEquals($thread->slug, 'unique-title');
        $this->assertEquals($thread2->slug, 'unique-title-'.$thread2->id);
    }

    public function test_auth_need_to_confirm_their_email_to_create_thread()
    {
        $this->signInConfirmedUser();
        $this->publishThread()->assertRedirect('/threads')->assertSessionHas('flash');
    }

    public function test_unauth_cant_delete_thread()
    {
        $this->withExceptionHandling();
        $thread = factoryCreate(\App\Models\Thread::class);
        $this->delete($thread->path())
        ->assertRedirect('/login');

        $this->signIn()->delete($thread->path())->assertStatus(403);
    }

    public function test_auth_user_can_delete_thread()
    {
        $this->signIn();
        $thread = factoryCreate(\App\Models\Thread::class, ['user_id' => auth()->id()]);
        $reply = factoryCreate(\App\Models\Reply::class, ['thread_id' => $thread->id]);
        // Attempt to delete the thread with json so it could be handled by controller easy
        $response = $this->json('DELETE', $thread->path());
        // Assert that the response has a successful status code
        $response->assertStatus(200);
        // Assert that the thread is deleted from the database
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        // Assert that the reply is deleted from the database
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        // // Assert that the activity is deleted from the database for all types Thread and Reply
        // $this->assertDatabaseMissing('activities', [
        //     'subject_id' => $thread->id,
        //     'subject_type' => get_class($thread),
        // ]);
        // $this->assertDatabaseMissing('activities', [
        //     'subject_id' => $reply->id,
        //     'subject_type' => get_class($reply),
        // ]);
        // Activity table is empty because it was deleted automatically all asociated activities
        $this->assertEquals(0, Activity::count());
    }

    public function test_a_thread_requires_a_title()
    {
        $this->signInConfirmedUser();
        $this->publishThread(['title' => null])->assertSessionHasErrors('title');
    }

    public function test_a_thread_requires_a_body()
    {
        $this->signInConfirmedUser();
        $this->publishThread(['body' => null])->assertSessionHasErrors('body');
    }

    public function test_a_thread_requires_a_valid_channel()
    {
        $this->signInConfirmedUser();
        $cannels = \App\Models\Channel::factory(2)->create();

        $this->publishThread(['channel_id' => null])->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id' => 99999])->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $thread = factoryMake(\App\Models\Thread::class, $overrides);

        return $this->post('/threads', $thread->toArray());
            
    }
}
