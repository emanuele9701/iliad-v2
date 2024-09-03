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
    <script>
        $(document).ready(function () {
            const productId = $('#product-id').text(); // Prende l'ID del prodotto dal testo dello span
            const apiUrl = URL_BASE_API + `/products/${productId}`;

            $.getJSON(apiUrl, function (object) {
                $('#product-id').text(object.data.id);
                $('#product-name').text(object.data.name);
                $('#description').text(object.data.description || 'Nessuna descrizione disponibile');
                $('#price').text(parseFloat(object.data.price).toFixed(2));
                const createdAt = new Date(object.data.created_at);
                const formattedCreatedAt = createdAt.toLocaleDateString('it-IT', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                const updatedAt = new Date(object.data.updated_at);
                const formattedUpdatedAt = updatedAt.toLocaleDateString('it-IT', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                $('#created-at').text(formattedCreatedAt);
                $('#updated-at').text(formattedUpdatedAt);

                const ordersList = $('#orders-list');
                object.data.orders.forEach(function (order) {
                    const row = `
            <tr onclick="window.location.href='../orders/${order.id}'" style="cursor:pointer;">
                <td>${order.id}</td>
                <td>${order.name}</td>
                <td>${new Date(order.order_date).toLocaleString()}</td>
            </tr>
        `;
                    ordersList.append(row);
                });

                $('#edit-product').attr('href', `/products/${productId}/edit`);
            }).fail(function (error) {
                console.error('Errore:', error);
            });
        });
    </script>
@endsection
