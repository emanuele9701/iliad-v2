<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home - Sistema di Gestione Ordini')
@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css" rel="stylesheet">
@endsection



@section('content')
    <div class="jumbotron text-center">
        <h1 class="display-4">Sistema di Gestione Ordini</h1>
        <p class="lead">Benvenuto nel tuo pannello di controllo per la gestione degli ordini giornalieri.</p>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">Visualizza Ordini</h5>
                    <p class="card-text">Accedi all'elenco completo degli ordini con possibilità di filtraggio e
                        ricerca.</p>
                    <a href="{{ route('orders.index') }}" class="btn btn-primary btn-lg btn-block">Lista Ordini</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">Nuovo Ordine</h5>
                    <p class="card-text">Crea un nuovo ordine inserendo i dettagli del cliente e i prodotti.</p>
                    <a href="{{ route('orders.create') }}" class="btn btn-success btn-lg btn-block">Crea Ordine</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestione Prodotti</h5>
                    <p class="card-text">Visualizza, aggiungi o modifica i prodotti disponibili nel catalogo.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-info btn-lg btn-block">Catalogo Prodotti</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ordini Recenti</h5>
                    <!-- Tabella ordini -->
                    <table id="table" data-url="{{route('api.orders.index')}}">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Valore</th>
                            <th>Data ordine</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiche</h5>
                    <p>Totale ordini: <strong id="totaleOrdini"></strong></p>
                    <p>Ordini oggi: <strong id="ordiniOdierni"></strong></p>
                    <p>Valore totale ordini: <strong id="sumOrdini"></strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .jumbotron {
            background-color: #f8f9fa;
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            border-radius: 0.3rem;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-lg {
            padding: 0.75rem 1.25rem;
            font-size: 1.25rem;
        }
    </style>
@endsection

@section('scripts')
    <script>
        var totaleOrdini = $("#totaleOrdini");
        var ordiniOdierni = $("#ordiniOdierni");
        var sumOrdini = $("#sumOrdini");


        function elaboroStatistiche(response) {
            totaleOrdini.text(response.totale_ordini);
            ordiniOdierni.text(response.ordini_odierni);
            sumOrdini.html("&euro; "+response.somma);
        }

        $(document).ready(function () {
            // Recupero le statistiche
            $.ajax({
                url:'api/orders/stats',
                method: 'get',
                dataType: 'json',
                success: elaboroStatistiche
            });

            const datatables = $("#table").DataTable({
                ajax: $("#table").attr('data-url'),
                language: {
                    url: "{{asset('i18n-datatables-it.json')}}"
                },
                processing: true,
                serverSide: true,
                searching: false,
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        name: 'id',
                    },
                    {
                        data: 'name',
                        orderable: false,
                        name: 'name',
                    },
                    {
                        data: 'total_value',
                        orderable: false,
                        name: 'total_value',
                        render: function (data,type,row) {
                            return "&euro; "+data;
                        }
                    },
                    {
                        data: 'order_date',
                        orderable: false,
                        name: 'order_date'
                    }
                ]
            });

        })

    </script>
@endsection
