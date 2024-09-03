<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app')

@section('title', 'Lista Ordini')
@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-4">
            <h1>Lista Ordini</h1>

        </div>
        <div class="col-8">
            <a class="btn btn-success float-end" href="{{route('orders.create')}}"><i class="bi bi-plus"></i><span>Aggiungi ordine</span></a>
        </div>
    </div>

    <table class="table" id="orders-table" data-url="{{ route('api.orders.index') }}">
        <thead>
        <tr>
            <th>#</th>
            <th>Nome Cliente</th>
            <th>Descrizione</th>
            <th>Prezzo</th>
            <th>Data</th>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th>
                <input type="text" class="form-control" onkeyup="searchTextTable(this);">
            </th>
            <th></th>
            <th></th>
            <th>
                <input type="date" class="form-control" onchange="searchTextTable(this);">
            </th>
            <th></th>
        </tr>
        </thead>
    </table>
@endsection

@section('scripts')
    @vite(['resources/js/orders.js'])
@endsection
