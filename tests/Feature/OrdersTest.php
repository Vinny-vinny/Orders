<?php

namespace Tests\Feature;

use App\Jobs\CreateOrderJob;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(): void
    {
     parent::setUp();
     $this->user = User::factory()->create();
    }

    public function test_order_listing_with_pagination_and_search()
    {
        // Seed some order data
        $this->actingAs($this->user);

        Product::factory()->count(2)->create();
        $orders = Order::factory()->count(5)->create();

        // Test without search term
        $response = $this->getJson('/api/orders?per_page=10&page=1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);

        // Test with a search term
        $response = $this->getJson('/api/orders?search=' . $orders[0]->order_number);
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('data'));
    }

    public function test_store_order_with_valid_data()
    {
        // Set up mock data
        Queue::fake();
        $product = Product::factory()->create();
        $orderData = [
            'product_id' => $product->id,
            'amount' => 500,
            'quantity' => 2,
        ];

        // Act as the authenticated user and send post request
        $response = $this->actingAs($this->user)->postJson('/api/orders', $orderData);

        // Check response status
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'user_id' => $this->user->id,
                'product_id' => $product->id,
                'amount' => 500,
                'quantity' => 2,
            ],
            'message' => 'Product has been added to the queue',
        ]);

        // Assert that the job was pushed to the queue
        Queue::assertPushed(CreateOrderJob::class);
    }

    public function test_store_order_with_invalid_data()
    {
        // Authenticate the user
        $this->actingAs($this->user);

        // Define invalid data for the request
        $invalidData = [
            'product_id' => 'invalid',
            'amount' => -5,
            'quantity' => 0,
        ];

        // Send the POST request with invalid data
        $response = $this->postJson('/api/orders', $invalidData);
        $response->assertStatus(422);


        // Assert the exact structure of validation errors under the 'data' key
        $response->assertJson([
            'success' => false,
            'message' => 'Validation error',
            'data' => [
                'product_id' => ['product id must be numeric'],
                'amount' => ['The amount field must be at least 1.'],
                'quantity' => ['The quantity field must be at least 1.'],
            ],
        ]);
    }


}
