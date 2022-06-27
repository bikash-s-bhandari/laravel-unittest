<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProductApiTest extends TestCase
{

    //this is just normal function and will not be included for test
    public function authenticate()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    public function test_create_product()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', 'api/v1/products', [
            'name' => 'Test product',
            'sku' => 'test-sku',
            'upc' => 'test-upc'
        ]);

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(201);
    }

    public function test_update_product()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('PUT', 'api/v1/products/2', [
            'name' => 'Test product111',
            'sku' => 'test-sku',
            'upc' => 'test-upc'
        ]);

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }


    public function test_get_all_product()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', 'api/v1/products');

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    public function test_delete_product()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('DELETE', 'api/v1/products/1');

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
}
