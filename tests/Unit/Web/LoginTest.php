<?php

namespace Tests\Unit\Web;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShowLoginForm()
    {
        $response = $this->get('/');
        $response->assertSuccessful();
        $response->assertViewIs('login');
        $response->assertSeeText('Email');
        $response->assertSeeText('Password');
    }

    public function testActiveUserLogin()
    {
        $user = factory(User::class)->create([
            'status' => User::STATUS_ACTIVE
        ]);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response
        ->assertStatus(302)
        ->assertRedirect('/dashboard');
    }

    public function testDisabledUserLogin()
    {
        $user = factory(User::class)->create([
            'status' => User::STATUS_DISABLED
        ]);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response
        ->assertRedirect('/login')
        ->assertSessionHasErrors('combination');
    }
}
