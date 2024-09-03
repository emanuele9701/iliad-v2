@extends('layouts.app')

@section('title', 'Dettagli Prodotto')

@section('content')
    <h1>Dettagli Prodotto <span id="product-id">{{ $product->id }}</span></h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nome: <span id="product-name"></span></h5>
            <p class="card-text">Descrizione: <span id="description"></span></p>
            <p class="card-text">Prezzo: €<span id="price"></span></p>
            <p class="card-text">Creato il: <span id="created-at"></span></p>
            <p class="card-text">Ultimo aggiornamento: <span id="updated-at"></span></p>
        </div>
    </div>

    <h2 class="mt-4">Ordini associati</h2>
    <table class="table">
        <thead>
        <tr>
            <th>ID Ordine</th>
            <th>Cliente</th>
            <th>Data Ordine</th>
        </tr>
        </thead>
        <tbody id="orders-list">
        </tbody>
    </table>

    <a href="#" class="btn btn-warning" id="edit-product">Modifica Prodotto</a>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Torna alla Lista</a>
@endsection

@section('scripts')
    @vite(['resources/js/products.js'])
@endsection
