<?php

namespace Tests\Feature\Auth;

use App\Mail\ConfirmYourEmail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_send_email_confirmation_when_user_registers(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'JohnDoe',
            'email' => 'jogn@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Mail::assertSent(ConfirmYourEmail::class);
    }

    public function test_auth_can_get_email_with_valid_link_for_confirmation(): void
    {

        $this->post('/register', [
            'name' => 'JohnDoe',
            'email' => 'jogn@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        // first user that is registered
        $registeredUser = User::whereName('JohnDoe')->first();
        // dd($registeredUser);
        $this->assertFalse($registeredUser->confirmed_email);

        $this->assertNotNull($registeredUser->confirmation_token);
        
        $response = $this->get(route('register.confirm', ['token'=> $registeredUser->confirmation_token]));

        $this->assertTrue($registeredUser->fresh()->confirmed_email);

        $this->assertNull($registeredUser->fresh()->confirmation_token);

        $response->assertRedirect(route('threads.create'));
    }

    public function test_detect_invalid_token(): void
    {
        $this->get(route('register.confirm', ['token'=> 'invalid_evil']))
        ->assertRedirect(route('threads.index'))
        ->assertSessionHas('flash', 'The confirmation link is invalid.');
    }
}
