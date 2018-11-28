<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Company;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testsFleetsAreCreatedCorrectly()
    {
        $payload = [
            'category' => 'Truck',
	        'car_make' => 'Mazda',
	        'plate_number' => '1234-gh',
	        'car_colour' => 'Purple'
        ];

        $this->json('POST', '/api/company', $payload )
            ->assertStatus(201)
            ->assertJson(['success'=> 'Fleet successffully created']);
    }

    public function testsFleetsAreUpdatedCorrectly()
    {
        $company = factory(Company::class)->create([
            'category' => 'Truck',
	        'car_make' => 'Mazda',
	        'plate_number' => '1234-gh',
	        'car_colour' => 'Purple'
        ]);

        $payload = [
            'category' => 'Truck',
	        'car_make' => 'Mazda',
	        'plate_number' => '1234-KUL',
	        'car_colour' => 'Green'
        ];

        $response = $this->json('PUT', '/api/company/' . $company->id, $payload )
            ->assertStatus(200)
            ->assertJson(['success'=> 'Successfully updated']);
    }

    public function testsFleetsAreDeletedCorrectly()
    {
        $contact = factory(Company::class)->create([
            'category' => 'Truck',
	        'car_make' => 'Mazda',
	        'plate_number' => '1234-gh',
	        'car_colour' => 'Purple'
        ]);

        $this->json('DELETE', '/api/company/' . $contact->id, [])
            ->assertStatus(204);
    }

    public function testsContactsAreSingleAndCorrect()
    {
        $contact = factory(Company::class)->create([
            'category' => 'Truck',
	        'car_make' => 'Mazda',
	        'plate_number' => '1234-gh',
	        'car_colour' => 'Purple'
        ]);

        $this->json('GET', '/api/company/' . $contact->id, [])
            ->assertStatus(200);
    }

    public function testsContactsAreListedCorrectly()
    {
        factory(Company::class)->create([
            'category' => 'Truck',
	        'car_make' => 'Mazda',
	        'plate_number' => '1234-gh',
	        'car_colour' => 'Purple'
        ]);

        factory(Company::class)->create([
            'category' => 'Truck',
	        'car_make' => 'Nissan',
	        'plate_number' => '1234-KUL',
	        'car_colour' => 'Yellow'
        ]);

        $response = $this->json('GET', '/api/company', [])
            ->assertStatus(200)
            ->assertJson([
                [ 'category' => 'Truck', 'num_of_fleet' => 2 ]
            ])
            ->assertJsonStructure([
                '*' => ['category', 'num_of_fleet'],
            ]);
    }
}
