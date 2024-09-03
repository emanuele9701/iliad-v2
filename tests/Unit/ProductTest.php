<?php

namespace Tests\Unit;

use App\Models\Order;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_product()
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 99.99,
        ];

        $product = Product::create($productData);

        $this->assertDatabaseHas('products', $productData);
        $this->assertEquals($productData['name'], $product->name);
        $this->assertEquals($productData['description'], $product->description);
        $this->assertEquals($productData['price'], $product->price);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create([
            'name' => 'Old Product',
            'description' => 'Old description.',
            'price' => 50.00,
        ]);

        $updatedData = [
            'name' => 'Updated Product',
            'description' => 'Updated description.',
            'price' => 75.00,
        ];

        $product->update($updatedData);

        $this->assertDatabaseHas('products', $updatedData);
        $this->assertEquals($updatedData['name'], $product->name);
        $this->assertEquals($updatedData['description'], $product->description);
        $this->assertEquals($updatedData['price'], $product->price);
    }

    /** @test */
    public function it_fails_validation_when_creating_with_invalid_data()
    {
        $response = $this->postJson('/api/products', [
            'name' => '', // Empty name should fail validation
            'description' => 'Valid description.',
            'price' => 100.00,
        ]);

        // Verifica che il codice di stato HTTP sia 422
        $response->assertStatus(422);

        // Verifica che l'errore di validazione per 'name' sia presente
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $product = Product::factory()->create();

        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
