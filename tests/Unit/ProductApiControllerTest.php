<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_product()
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 99.99,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'Test Product',
                    'description' => 'This is a test product.',
                    'price' => 99.99,
                    // Include additional fields based on ProductResource
                    'created_at' => true, // Use true to check presence of these fields
                    'updated_at' => true,
                    'id' => true,
                    'orders' => [], // Assuming orders is an empty array
                ]
            ]);

        $this->assertDatabaseHas('products', $data);
    }

    /** @test */
    public function it_can_search_products()
    {
        Product::factory()->create(['name' => 'Test Product']);
        Product::factory()->create(['name' => 'Another Product']);

        $response = $this->getJson('/api/products/search?q=Test');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'items')
            ->assertJsonFragment(['name' => 'Test Product']);
    }

    /** @test */
    public function it_cannot_create_a_product_with_invalid_data()
    {
        $data = [
            'name' => '', // Invalid data
            'description' => '',
            'price' => -10, // Invalid data
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'price']);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        // Crea un prodotto esistente per aggiornare
        $product = Product::factory()->create([
            'name' => 'Old Product',
            'description' => 'Old description.',
            'price' => 50,
        ]);

        // I nuovi dati da aggiornare
        $data = [
            'name' => 'Updated Product',
            'description' => 'Updated description.',
            'price' => 75,
        ];

        // Invio della richiesta PATCH per aggiornare il prodotto
        $response = $this->patchJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => 'Updated Product',
                    'description' => 'Updated description.',
                    'price' => 75,
                    'created_at' => true,
                    'updated_at' => true,
                    'orders' => [], // Assumendo che orders è un array vuoto
                ]
            ]);

        // Verifica che il prodotto nel database sia stato aggiornato
        $this->assertDatabaseHas('products', $data);
    }

    /** @test */
    public function it_cannot_update_a_product_with_invalid_data()
    {
        $product = Product::create([
            'name' => 'Old Product',
            'description' => 'Old description.',
            'price' => 50.00,
        ]);

        $data = [
            'name' => '', // Invalid data
            'price' => -20, // Invalid data
        ];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'price']);
    }
}
