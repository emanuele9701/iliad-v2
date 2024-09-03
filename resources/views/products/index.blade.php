@extends('layouts.app')

@section('title', 'Lista Prodotti')

@section('content')
    <div class="container"><div class="row">
            <div class="col-4">
                <h1>Lista prodotti</h1>

            </div>
            <div class="col-8">
                <a class="btn btn-success float-end" href="{{ route('products.create') }}"><i class="bi bi-plus"></i><span>Aggiungi nuovo prodotto</span></a>
            </div>
        </div>


        <div class="table-responsive">
            <table id="products-table" class="table table-striped table-hover">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrizione</th>
                    <th>Prezzo</th>
                    <th>Azioni</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/products.js'])
@endsection

@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .dt-search {
            display: inline;
        }
    </style>
@endsection
