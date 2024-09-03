$(document).ready(function () {
    // Index
    if ($("#orders-table").length > 0) {
        // Viene caricato solamente se ci troviamo nella index degli ordini
        var table = new DataTable('#orders-table', {
            ajax: $("#orders-table").attr('data-url'),
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.5/i18n/it-IT.json'
            },
            processing: true,
            serverSide: true,
            searching: true,
            columns: [
                {
                    data: 'id',
                    orderable: false,
                    name: 'id',
                    searchable: false,
                },
                {
                    data: 'name',
                    orderable: false,
                    searchable: true,
                    name: 'name',
                },
                {
                    data: 'description',
                    orderable: false,
                    name: 'description', searchable: false,
                },
                {
                    data: 'total_value',
                    orderable: false,
                    searchable: false,
                    name: 'total_value',
                    render: function (data, type, row) {
                        return "&euro; " + data;
                    }
                },
                {
                    data: 'order_date',
                    searchable: true,
                    orderable: false,
                    name: 'order_date'
                },
                {
                    data: 'actions',
                    searchable: false,
                    orderable: false,
                    name: 'actions'
                }
            ],
        });


        table.on('draw.dt', function (a, b, rows) {
            $('div.actions button.btn-danger:not(div.modal button.btn-danger)').on('click', openFormElimina);

            $('div.actions div.modal button.btn-danger').each(function (index, element) {
                if (!$(element).data('click')) {
                    handleButtonClick(index, element, table.data().toArray());
                }
            });
        });

        function handleButtonClick(index, element, rows) {
            $(element).on('click', function () {
                $($('div.actions div.modal')[index]).find('.spinner-border').removeClass('d-none');
                var id = 0;
                if (rows.data !== undefined) {
                    id = rows.data[index].id;
                } else {
                    id = rows[index].id;
                }
                $.ajax({
                    url: 'api/orders/' + id,
                    method: 'delete',
                    success: function (response) {
                        $($('div.actions div.modal')[index]).find('.spinner-border').addClass('d-none');
                        $($('div.actions div.modal')[index]).modal('toggle'); // Chiudo il modal corrente
                        if (response.esito) {
                            // Ricarico la tabella
                            table.draw();
                            $("#modalSuccessSimple").modal('show');
                        }
                    },
                    error: function (response) {
                        $($('div.actions div.modal')[index]).find('.spinner-border').addClass('d-none');
                        $("#modalErrorSimple").modal('show'); // Visualizzo un modal di errore semplice. Si trova nell app.blade
                    }
                });
            });
        }


        window.searchTextTable = function searchTextTable(element) {
            if (element.value.length > 2) {
                var index = $(element).parent().parent().index();

                table.column(index).search(element.value).draw();
            }
        }
    }
    // Edit / Create
    else if ($("#container-ordine").length > 0) {
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
                    url = URL_BASE_API + '/orders';
                    method = 'post';
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
    } else {
        // Show
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

    }
    $.ajaxSetup({
        headers:
            {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
});

function recuperoInfoOrdine() {
    var ordine = null;
    $.ajax({
        async: false,
        url: URL_BASE_API + '/orders/' + $("input[name='id_ordine']").val(),
        method: 'get',
        success: function (response) {
            if (response.data) {
                ordine = response.data;
            }
            return false;
        }
    });
    return ordine;
}


