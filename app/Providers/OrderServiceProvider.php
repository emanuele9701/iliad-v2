<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OrderServiceProvider::class, function ($app) {
            return new OrderServiceProvider($app);
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
     * Crea un nuovo ordine con i prodotti associati.
     *
     * @param array $data
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        // Utilizzare una transazione per garantire l'integrità dei dati
        return DB::transaction(function () use ($data) {
            // Creare l'ordine
            $order = Order::create([
                'name' => $data['customer_name'],
                'description' => $data['description'],
                'order_date' => $data['order_date'],
                'total_value' => 0, // Sarà calcolato dopo
            ]);

            // Aggiungere i prodotti all'ordine e calcolare il valore totale
            $totalValue = 0;
            foreach ($data['products'] as $productData) {
                $product = Product::find($productData['id']);
                if ($product) {
                    $order->products()->attach($product->id, ['qty' => $productData['quantity'],'name' => $product->name,'price' => $product->price,'description' => $product->description]);
                    $totalValue += $product->price * $productData['quantity'];
                }
            }

            // Aggiornare il valore totale dell'ordine
            $order->update(['total_value' => $totalValue]);

            return $order;
        });
    }

    /**
     * Aggiorna un ordine esistente con nuovi dati e prodotti.
     *
     * @param Order $order
     * @param array $data
     * @return Order
     */
    public function updateOrder(Order $order, array $data): Order
    {
        return DB::transaction(function () use ($order, $data) {
            // Aggiornare i dati di base dell'ordine
            $order->update([
                'name' => $data['customer_name'],
                'description' => $data['description'],
                'order_date' => $data['order_date'],
            ]);

            // Sincronizzare i prodotti
            $order->products()->detach();
            $totalValue = 0;

            foreach ($data['products'] as $productData) {
                $product = Product::find($productData['id']);
                if ($product) {
                    $order->products()->attach($product->id, ['qty' => $productData['quantity'],'name' => $product->name,'price' => $product->price,'description' => $product->description]);
                    $totalValue += $product->price * $productData['quantity'];
                }
            }

            // Aggiornare il valore totale
            $order->update(['total_value' => $totalValue]);

            return $order;
        });
    }
}
