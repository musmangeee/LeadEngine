<?php

namespace Tests\Unit\Api;

use App\Models\Provider;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProviderTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    public function testProviderList()
    {
        $providers = factory(Provider::class, 11)->create();
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $response = $this->getJson('/api/providers', [
            'Accept' => 'application/json'
        ]);

         //make sure response total is right
         $response->assertJsonFragment([
             'total' => 11
         ]);

        //make sure response total is right
        $response->assertJsonFragment($providers->first()->toArray());
    }

    public function testProviderView()
    {
        $provider = factory(Provider::class)->create();
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $response = $this->getJson('/api/providers/'.$provider->id, [
            'Accept' => 'application/json'
        ]);
        //make sure response is right
        $response->assertJsonFragment($provider->toArray());
    }

    public function testProviderEdit()
    {
        $provider = factory(Provider::class)->create();
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $updateData = [
            'name' => 'Another Provider'
        ];

        $response = $this->putJson('/api/providers/'.$provider->id, array_merge($provider->toArray(), $updateData), [
            'Accept' => 'application/json'
        ]);

        //make sure response is right
        $response->assertJsonFragment($updateData);
        //make sure lead updated
        $this->assertDatabaseHas('providers', array_merge(['id' => $provider->id], $updateData));
    }

    public function testProviderDelete()
    {
        $provider = factory(Provider::class)->create();
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $response = $this->deleteJson('/api/providers/'.$provider->id, [], [
            'Accept' => 'application/json'
        ]);

        //make sure response is right
        $response->assertJsonFragment([
            'status' => 'success'
        ]);

        //make sure lead deleted
        $this->assertDatabaseMissing('applications', [
            'id' => $provider->id,
        ]);
    }

}
