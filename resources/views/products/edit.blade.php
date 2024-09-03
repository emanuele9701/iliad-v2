@extends('layouts.app')

@section('title', 'Modifica Prodotto')
@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .spinner-border {
            --bs-spinner-width: 1rem !important;
            --bs-spinner-height: 1rem !important;
        }
    </style>
@endsection

@section('content')
    <input type="hidden" name="id_prodotto" value="{{$product->id}}">
    <h1>Modifica Prodotto #<span id="spn-id"></span></h1>
    <form>
        <div class="" id="container-prodotto">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name">Nome Prodotto</label>
                        <input type="text" class="form-control" id="name" name="name" value="" required>
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
                        <label for="price">Prezzo</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">€</span>
                            </div>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Aggiorna Prodotto<div class="spinner-border ms-2 d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div></button>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Annulla</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            var prodotto = recuperoInfoProdotto();

            if (prodotto != null) {
                // Prodotto caricato
                $("#name").val(prodotto.name);
                $("#spn-id").text(prodotto.id);
                $("textarea[name='description']").val(prodotto.description);
                $("#price").val(prodotto.price);
            }

            $("form").on('submit', function (e) {
                $("button[type='submit']").attr('disabled', '');
                $("button[type='submit']").find('.spinner-border').removeClass('d-none');

                e.preventDefault();
                var url = "";
                var method = "";
                url = URL_BASE_API + '/products/' + $("input[name='id_prodotto']").val() + "";
                method = 'put';
                var serializedForm = $(this).serializeArray();
                $.ajax({
                    url: url,
                    method: method,
                    data: serializedForm,
                    success: function (response) {
                        $("button[type='submit']").find('.spinner-border').addClass('d-none');
                        $("button[type='submit']").removeAttr('disabled');
                        if (response.data) {
                            $("#modalSuccessSimple").modal('show');
                        } else {
                            $("#modalErrorSimple").modal('show');
                        }
                    },
                    error: function (response) {
                        $("button[type='submit']").find('.spinner-border').addClass('d-none');
                        $("button[type='submit']").removeAttr('disabled');
                        $("#modalErrorSimple div.modal-body span#errore").text("");
                        if (response.responseJSON.message !== undefined) {
                            setTimeout(function () {
                                $("#modalErrorSimple").modal('show');
                                $("#modalErrorSimple div.modal-body span#errore").text(response.responseJSON.message);
                            }, 1500);
                        }
                    }
                });
            });
        });


    </script>
@endsection
