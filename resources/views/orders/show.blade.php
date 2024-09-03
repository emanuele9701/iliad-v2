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
    <script>
        $(document).ready(function () {
            const orderId = $('#order-id').text(); // Prende l'ID dell'ordine dal testo dello span
            const apiUrl = URL_BASE_API + `/orders/${orderId}`;

            $.getJSON(apiUrl, function (object) {
                $('#order-id').text(object.data.id);
                $('#customer-name').text(object.data.name);
                $('#description').text(object.data.description);
                const orderDate = new Date(object.data.order_date);
                const formattedDate = orderDate.toLocaleDateString('it-IT', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                $('#created-at').text(formattedDate);

                const productsList = $('#products-list');
                object.data.products.forEach(function (product) {
                    const row = `
                <tr>
                    <td>${product.name}</td>
                    <td>${product.qty}</td>
                    <td>€${parseFloat(product.price).toFixed(2)}</td>
                    <td>€${(product.price * product.qty).toFixed(2)}</td>
                </tr>
            `;
                    productsList.append(row);
                });

                $('#edit-order').attr('href', `/orders/${orderId}/edit`);
            }).fail(function (error) {
                console.error('Errore:', error);
            });
        });
    </script>
@endsection
