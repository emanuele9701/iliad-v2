<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Providers\OrderServiceProvider;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderApiController extends Controller
{

    protected $orderService;

    public function __construct(OrderServiceProvider $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::orderByDesc('order_date')->select(['id', 'name', 'total_value', 'order_date', 'description']);
        $table = DataTables::of($orders);

        $table->addColumn('actions', function ($row) {
            /**
             * @var Order $row
             */
            return view('orders.tables.buttons', ['order' => $row]);
        });

        $table->filterColumn('name', function ($query, $keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        });

        $table->filterColumn('order_date', function ($query, $keyword) {
            $query->where('order_date', $keyword);
        });

        return $table->make(true);
    }

    public function stats()
    {
        // Restituisce le statistiche degli ordini
        $totaleOrdini = Order::count();
        $ordiniOdierni = Order::where('order_date', now())->count();
        $sommaImportoOrdini = Order::sum('total_value');

        return response()->json(['totale_ordini' => $totaleOrdini, 'ordini_odierni' => $ordiniOdierni, 'somma' => $sommaImportoOrdini, 'esito' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            // Validare i dati in ingresso
            $validatedData = $request->all();

            $order = $this->orderService->createOrder($validatedData);

            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json(['esito' => false, 'message' => 'Errore durante la creazione dell\'ordine.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new OrderResource($order->load('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {

        // Validare i dati in ingresso
        $validatedData = $request->all();

        $order = $this->orderService->updateOrder($order,$validatedData);

        return new OrderResource($order);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['esito' => true]);
    }
}
