<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VolunteerJoinedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+212600000001',
            'city' => 'Casablanca',
            'languages' => 'Arabic, French, English',
            'skills' => 'Crowd guidance, logistics',
            'availability' => 'weekends',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard', absolute: false));
        $this->assertSame(User::ROLE_ADMIN, User::where('email', 'test@example.com')->value('role'));
        Notification::assertNothingSent();
    }

    public function test_users_registered_after_the_first_user_get_volunteer_role(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->post('/register', [
            'name' => 'Volunteer User',
            'email' => 'volunteer@example.com',
            'phone' => '+212600000002',
            'city' => 'Rabat',
            'languages' => 'Arabic, French',
            'skills' => 'First aid, coordination',
            'availability' => 'weekdays',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('volunteer.dashboard', absolute: false));
        $this->assertSame(User::ROLE_VOLUNTEER, User::where('email', 'volunteer@example.com')->value('role'));
        Notification::assertSentTo($admin, VolunteerJoinedNotification::class);
    }
}
