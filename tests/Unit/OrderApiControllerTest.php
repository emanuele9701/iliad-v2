<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_an_order()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'John Doe',
            'description' => 'Test Order Description',
            'order_date' => now()->toDateString(), // Aggiungi order_date
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'name' => 'John Doe',
                'description' => 'Test Order Description',

            ])
            ->assertJsonFragment([
                'products' => [
                    [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => number_format($product->price,2,".",""),
                        'qty' => 2,
                    ],
                ],
            ]);
    }

    /** @test */
    /** @test */
    public function it_can_update_an_order()
    {
        $order = Order::create([
            'name' => 'Old Name',
            'description' => 'Old Description',
            'order_date' => now(),
            'total_value' => 0,
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'price' => 87.00, // Assicurati che il prezzo sia in formato float
        ]);

        $order->products()->attach($product->id, [
            'qty' => 1,
            'name'=>$product->name,
            'price'=>$product->price,
            'description'=>$product->description
        ]);

        $response = $this->putJson("/api/orders/{$order->id}", [
            'customer_name' => 'Updated Name',
            'description' => 'Updated Description',
            'order_date' => now()->toDateString(), // Aggiungi order_date
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 3,
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'name' => 'Updated Name',
                'description' => 'Updated Description',
            ])
            ->assertJsonFragment([
                'products' => [
                    [
                        'id' => $product->id,
                        'name' => 'Test Product',
                        'price' => '87.00', // Modifica per riflettere il formato della risposta
                        'qty' => 3,
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_can_show_an_order()
    {
        $order = Order::create([
            'name' => 'Test Order',
            'description' => 'Test Description',
            'order_date' => now(),
            'total_value' => 100.00,
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'price' => 50.00,
        ]);

        $order->products()->attach($product->id, [
            'qty' => 2,
            'name'=>$product->name,
            'price'=>$product->price,
            'description'=>$product->description
        ]);

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'name' => 'Test Order',
                'description' => 'Test Description',
                'products' => [
                    [
                        'id' => $product->id,
                        'name' => 'Test Product',
                        'price' => '50.00',
                        'qty' => 2,
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_can_destroy_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(Response::HTTP_OK);
        // Verifica che il record sia stato contrassegnato come eliminato (Soft Deletes)
        $this->assertSoftDeleted('orders', [
            'id' => $order->id,
        ]);
    }

    /** @test */
    public function it_can_list_orders()
    {
        $orders = Order::factory()->count(3)->create();

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_get_order_stats()
    {
        Order::factory()->count(5)->create();

        $response = $this->getJson('/api/orders/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'totale_ordini',
                'ordini_odierni',
                'somma',
                'esito'
            ]);
    }

}
