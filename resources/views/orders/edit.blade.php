<!-- resources/views/orders/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifica Ordine')
@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            let productIndex = 0;
            var ordine = recuperoInfoOrdine();

            if (ordine != null) {
                // Ordine caricato
                $("#customer_name").val(ordine.name);
                $("#order_date").val(ordine.order_date);
                $("#spn-id").text(ordine.id);
                $("textarea[name='description']").val(ordine.description);
                var costoTotaleOrdine = 0;
                // Inserisco i prodotti
                ordine.products.forEach(function (element, index) {
                    var costoTotale = element.price * element.qty;
                    costoTotaleOrdine += costoTotale;
                    const newProduct = createProductRow(element.id, element.qty, element.name, element.price, costoTotale);
                    $('#products-container').append(newProduct);
                    productIndex++;
                });

                $("#prezzoTotaleOrdine").html("&euro; " + costoTotaleOrdine.toFixed(2));
            }


            $(document).on('click', '.remove-product', function () {
                $(this).closest('.product-row').remove();
                updateTotalPrice();
            });

            $(document).on('click', '#add-product', function () {
                const newProduct = createProductRow('', 1, '', 0, 0, true);
                $('#products-container').append(newProduct);
                productIndex++;
                initializeSelect2();
            });

            function createProductRow(id, qty, name, price, total, n = false) {
                var productCel = '';
                if (n) {
                    productCel = '<select class="form-control product-name"  name="products[' + productIndex + '][name]" required></select>';
                } else {
                    productCel = '<span>' + name + '</span>';
                }
                return `
            <div class="product-row mb-3">
                <input type="hidden" name="products[${productIndex}][id]" id="product_id" value="${id}">
                <div class="d-flex justify-content-evenly">
                    <div class="col-2 me-1">
                        <input type="number" class="form-control w-50 product-quantity" name="products[${productIndex}][quantity]" value="${qty}" placeholder="Quantità" required>
                    </div>
                    <div class="col-5">
                        ${productCel}
                    </div>
                    <div class="col-2">
                        <span class="product-price">&euro; ${price}</span>
                    </div>
                    <div class="col-2">
                        <span class="product-total">&euro; ${total.toFixed(2)}</span>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-danger remove-product">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
            }

            function initializeSelect2() {
                $('.product-name').select2({
                    ajax: {
                        url: URL_BASE_API + '/products/search',
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: data.items.map(function (product) {
                                    return {
                                        id: product.id,
                                        text: product.name,
                                        price: product.price
                                    };
                                })
                            };
                        }
                    }
                }).on('select2:select', function (e) {
                    var selectedProduct = e.params.data;
                    var $row = $(this).closest('.product-row');
                    $row.find('.product-price').html(`&euro; ${selectedProduct.price}`);
                    $row.find('#product_id').val(selectedProduct.id);
                    updateProductTotal($row);
                });
            }

            function updateProductTotal($row) {
                var qty = $row.find('.product-quantity').val();
                var price = parseFloat($row.find('.product-price').text().replace('€ ', ''));
                var total = qty * price;
                $row.find('.product-total').html(`&euro; ${total.toFixed(2)}`);
                updateTotalPrice();
            }

            function updateTotalPrice() {
                var total = 0;
                $('.product-total').each(function () {
                    total += parseFloat($(this).text().replace('€ ', ''));
                });
                $("#prezzoTotaleOrdine").html("&euro; " + total.toFixed(2));
            }

            $(document).on('change', '.product-quantity', function () {
                var $row = $(this).closest('.product-row');
                updateProductTotal($row);
            });

            // Initialize Select2 for existing products
            initializeSelect2();

            $("form").on('submit', function (e) {
                $("button[type='submit']").attr('disabled', '');
                $("button[type='submit']").find('.spinner-border').removeClass('d-none');

                e.preventDefault();
                var url = "";
                var method = "";
                if (ordine) {
                    url = URL_BASE_API + '/orders/' + $("input[name='id_ordine']").val() + "";
                    method = 'put';
                } else {
                    $("#modalErrorSimple").modal('show');
                    $("#modalErrorSimple div.modal-body span#errore").text("Si è verificato un problema, riprova");
                    return;
                }
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
                })
            })
        });
    </script>
@endsection
