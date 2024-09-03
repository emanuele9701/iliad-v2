<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use App\Providers\OrderServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_order_can_be_created()
    {
        $order = Order::create([
            'name' => 'Test Order',
            'description' => 'Test Description',
            'order_date' => now(),
            'total_value' => 100.00,
        ]);

        $this->assertDatabaseHas('orders', [
            'name' => 'Test Order',
            'description' => 'Test Description',
            'total_value' => 100.00,
        ]);
    }

    /** @test */
    public function it_calculates_total_value_automatically()
    {
        $orderService = app(OrderServiceProvider::class);
        $order = Order::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $qty1 = rand(1,10);
        $qty2 = rand(1,10);

        $data = [
            'customer_name' => $order->name,
            'description' => $order->description,
            'order_date' => $order->order_date,
            'total_value' => 0,
            'products' => [array_merge($product1->toArray(),['quantity' => $qty1]),array_merge($product2->toArray(),['quantity' => $qty2])]
        ];

        $mytotal = $qty2 * $product2->price + $qty1 * $product1->price;

        $order = $orderService->createOrder($data);


        $this->assertEquals($mytotal, $order->total_value);
    }

    /** @test */
    public function an_order_can_have_products()
    {
        $order = Order::create([
            'name' => 'Test Order',
            'description' => 'Test Description',
            'order_date' => now(),
            'total_value' => 0,
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

        $this->assertDatabaseHas('products_order', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'qty' => 2,
        ]);
    }
}
