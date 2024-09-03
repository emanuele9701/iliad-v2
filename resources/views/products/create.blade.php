@extends('layouts.app')

@section('title', 'Crea Nuovo Prodotto')
@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .spinner-border {
            --bs-spinner-width: 1rem !important;
            --bs-spinner-height: 1rem !important;
        }
        .error-message {
            color: red;
            font-size: 0.9em;
        }
    </style>
@endsection

@section('content')
    <h1>Crea Nuovo Prodotto</h1>
    <form id="productForm">
        <div class="" id="container-prodotto">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name">Nome Prodotto</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="error-message" id="name-error"></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="description">Descrizione</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        <div class="error-message" id="description-error"></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="price">Prezzo</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">€</span>
                            </div>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>
                        <div class="error-message" id="price-error"></div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Crea Prodotto<div class="spinner-border ms-2 d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div></button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Torna ai prodotti</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/products.js'])
@endsection
