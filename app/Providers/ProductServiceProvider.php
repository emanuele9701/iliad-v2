<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ProductServiceProvider::class, function ($app) {
            return new ProductServiceProvider($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Crea un nuovo prodotto.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        // Creazione del prodotto
        return Product::create($data);
    }

    /**
     * Aggiorna un prodotto esistente.
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function updateProduct(Product $product, array $data): Product
    {

        // Aggiornamento dei dati del prodotto
        $product->update($data);

        return $product;
    }
}
