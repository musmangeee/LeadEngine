<?php

namespace Tests\Unit\Api;

use App\Models\Application;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class Lead extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    // public function testLeadList()
    // {
    //     $leads = factory(Application::class, 110)->create();
    //     $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
    //     Passport::actingAs($user);

    //     $response = $this->getJson('/api/leads', [
    //         'Accept' => 'application/json'
    //     ]);

    //     //make sure response total is right
    //     $response->assertJsonFragment([
    //         'total' => $leads->count()
    //     ]);


    //     $paginationPerPage = 30;
    //     $response = $this->getJson('/api/leads?perPage='.$paginationPerPage, [
    //         'Accept' => 'application/json'
    //     ]);
        
    //     //make sure pagination is applied
    //     $response->assertJsonFragment([
    //         'per_page' =>  $paginationPerPage
    //     ]);

    //     $filter = $leads[0]->email;
    //     $response = $this->getJson('/api/leads?filter='.$filter, [
    //         'Accept' => 'application/json'
    //     ]);
        
    //     //make the result is filtered
    //     $response->assertJsonFragment($leads[0]->toArray());
    // }

    // public function testLeadView()
    // {
    //     $lead = factory(Application::class)->create();
    //     $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
    //     Passport::actingAs($user);

    //     $response = $this->getJson('/api/leads/'.$lead->id, [
    //         'Accept' => 'application/json'
    //     ]);
    //     //make sure response is right
    //     $response->assertJsonFragment($lead->toArray());
    // }

    // public function testLeadEdit()
    // {
    //     $lead = factory(Application::class)->create();
    //     $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
    //     Passport::actingAs($user);

    //     $updateData = [
    //         'address_months' => 48,
    //         'address_years' => 4
    //     ];

    //     $response = $this->putJson('/api/leads/'.$lead->id, array_merge($lead->toArray(), $updateData), [
    //         'Accept' => 'application/json'
    //     ]);

    //     //make sure response is right
    //     $response->assertJsonFragment($updateData);
    //     //make sure lead updated
    //     $this->assertDatabaseHas('applications', array_merge(['id' => $lead->id], $updateData));
    // }

    // public function testLeadDelete()
    // {
    //     $lead = factory(Application::class)->create();
    //     $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
    //     Passport::actingAs($user);

    //     $response = $this->deleteJson('/api/leads/'.$lead->id, [], [
    //         'Accept' => 'application/json'
    //     ]);

    //     //make sure response is right
    //     $response->assertJsonFragment([
    //         'status' => 'success'
    //     ]);

    //     //make sure lead deleted
    //     $this->assertDatabaseMissing('applications', [
    //         'id' => $lead->id,
    //     ]);
    // }
}
