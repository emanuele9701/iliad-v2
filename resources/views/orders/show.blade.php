@extends('layouts.app')

@section('title', 'Dettagli Ordine')

@section('content')
    <h1>Dettagli Ordine <span id="order-id">{{$order->id}}</span></h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Cliente: <span id="customer-name"></span></h5>
            <p class="card-text">Descrizione: <span id="description"></span></p>
            <p class="card-text">Data: <span id="created-at"></span></p>
        </div>
    </div>

    <h2 class="mt-4">Prodotti associati</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Nome Prodotto</th>
            <th>Quantità</th>
            <th>Prezzo Unitario</th>
            <th>Totale</th>
        </tr>
        </thead>
        <tbody id="products-list">
        </tbody>
    </table>

    <a href="#" class="btn btn-warning" id="edit-order">Modifica Ordine</a>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Torna alla Lista</a>
@endsection
@section('scripts')
    @vite(['resources/js/orders.js'])
@endsection
