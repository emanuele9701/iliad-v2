<!-- resources/views/orders/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifica Ordine')
@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @vite(['resources/js/lib/select2/dist/css/select2.min.css'])
    <style>
        .spinner-border {
            --bs-spinner-width: 1rem !important;
            --bs-spinner-height: 1rem !important;
        }
    </style>
@endsection

@section('content')
    <input type="hidden" name="id_ordine" value="{{$order->id}}">
    <h1>Modifica Ordine #<span id="spn-id"></span></h1>
    <form>

        <div class="" id="container-ordine">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="customer_name">Nome Cliente</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" value="" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="description">Descrizione</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="description">Data ordine</label><input type="date" class="form-control" id="order_date" name="order_date" value="" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h2 class="mt-4">Prodotti</h2>
                </div>
                <div class="col-12">
                    <div class="product-row mb-3">
                        <div class="d-flex justify-content-evenly fw-bold">
                            <div class="col-2 me-1">
                                <span>Qt.</span>
                            </div>
                            <div class="col-5">
                                <span>Nome prodotto</span>
                            </div>
                            <div class="col-2">
                                <span>Prezzo</span>
                            </div>
                            <div class="col-2">
                                <span>Prezzo totale</span>
                            </div>
                            <div class="col-1"></div>
                        </div>
                    </div>
                    <div id="products-container">

                    </div>
                    <div class="product-row mb-3">
                        <div class="d-flex justify-content-evenly fw-bold">
                            <div class="col-9 text-end">
                                <span>Totale ordine</span>
                            </div>
                            <div class="col-3">
                                <span class="mx-1" id="prezzoTotaleOrdine"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="button" id="add-product" class="btn btn-default mb-3"><i class="bi bi-plus"></i> Aggiungi Prodotto</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Aggiorna Ordine<div class="spinner-border ms-2 d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div></button>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Annulla</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/lib/select2/dist/js/select2.full.min.js','resources/js/orders.js'])
@endsection
