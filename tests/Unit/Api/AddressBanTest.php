<?php

namespace Tests\Unit\Api;

use App\Models\AddressBan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddressBanTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    public function testAddressBanList()
    {
        $addressBans = factory(AddressBan::class, 11)->create();
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $response = $this->getJson('/api/address-ban', [
            'Accept' => 'application/json'
        ]);

        //make sure response total is right
        $response->assertJsonFragment([
            'data' => $addressBans->toArray()
        ]);
    }

    public function testAddressBanCreate()
    {
        $addressBan = factory(AddressBan::class)->make();
        $createData = [
            'type' => !empty($addressBan->hostname) ? AddressBan::TYPE_HOSTNAME : AddressBan::TYPE_IP_ADDRESS,
            'address' => !empty($addressBan->hostname) ? $addressBan->hostname: $addressBan->ip_address
        ];

        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $response = $this->postJson('/api/address-ban', $createData, [
            'Accept' => 'application/json'
        ]);

        //make sure response is right
        $response->assertJson([
            'data' => $addressBan->only(['hostname','ip_address'])
        ]);

        //make sure new row has been added to database
        $this->assertDatabaseHas('address_bans', $addressBan->only(['hostname','ip_address']));
    }

    public function testAddressBanCreateValidation()
    {
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $emptyAddressBan = [];
        $response = $this->postJson('/api/address-ban', $emptyAddressBan, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422)
        ->assertJsonFragment([
            'errors' => [
                'type' => ['The type field is required.'],
                'address' => ['The address field is required.']
            ]
        ]);

        $existingAddressBan = factory(AddressBan::class)->create(['hostname' => 'google.com']);
        $duplicatedAddressBan = [
            'type' => 'hostname',
            'address' => 'google.com'
        ];

        $response = $this->postJson('/api/address-ban', $duplicatedAddressBan, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422)
        ->assertJsonFragment([
            'errors' => [
                'address' => ['Data already exists.']
            ]
        ]);
    }

    public function testAddressBanShow()
    {
        $addressBan = factory(AddressBan::class)->create();
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $response = $this->getJson('/api/address-ban/'.$addressBan->id, [
            'Accept' => 'application/json'
        ]);

        //make sure response is right
        $response->assertJson([
            'data' => $addressBan->only(['hostname','ip_address'])
        ]);
    }

    public function testAddressBanUpdate()
    {
        $addressBan = factory(AddressBan::class)->create();

        $newData = factory(AddressBan::class)->make();
        $updateData = [
            'type' => !empty($newData->hostname) ? AddressBan::TYPE_HOSTNAME : AddressBan::TYPE_IP_ADDRESS,
            'address' => !empty($newData->hostname) ? $newData->hostname: $newData->ip_address
        ];

        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $response = $this->putJson('/api/address-ban/'.$addressBan->id, $updateData, [
            'Accept' => 'application/json'
        ]);

        //make sure response is right
        $response->assertJson([
            'data' => $newData->only(['hostname','ip_address'])
        ]);

        //make sure new row has been added to database
        $this->assertDatabaseHas('address_bans', $newData->only(['hostname','ip_address']));
    }


    public function testAddressBanUpdateValidation()
    {
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $existingAddressBan = factory(AddressBan::class)->create(['hostname' => 'google.com']);

        $emptyAddressBan = [];
        $response = $this->putJson('/api/address-ban/'.$existingAddressBan->id, $emptyAddressBan, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422)
        ->assertJsonFragment([
            'errors' => [
                'type' => ['The type field is required.'],
                'address' => ['The address field is required.']
            ]
        ]);

        $duplicatedAddressBan = [
            'type' => 'hostname',
            'address' => 'google.com'
        ];

        $response = $this->putJson('/api/address-ban/'.$existingAddressBan->id, $duplicatedAddressBan, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200)
        ->assertJsonFragment($existingAddressBan->only(['hostname']));
    }

    public function testAddressBanDelete()
    {
        $addressBan = factory(AddressBan::class)->create();

        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        Passport::actingAs($user);

        $response = $this->deleteJson('/api/address-ban/'.$addressBan->id, [], [
            'Accept' => 'application/json'
        ]);

        //make sure response is right
        $response->assertJson([
            'status' => 'success'
        ]);

        //make sure new row has been added to database
        $this->assertDatabaseMissing('address_bans', $addressBan->only(['hostname','ip_address']));
    }
}
