if ($("#products-table").length > 0) {
    // Index
    $(document).ready(function () {
        var table = $('#products-table').DataTable({
            "processing": true,
            "serverSide": true,
            searching: true,
            "ajax": URL_BASE_API + "/products",
            "columns": [
                {"data": "id"},
                {
                    "data": "name",
                    searchable: true
                },
                {"data": "description"},
                {
                    "data": "price",
                    "render": function (data, type, row) {
                        return '€' + parseFloat(data).toFixed(2);
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return `<div class="d-flex flex-column actions">
    <a class="btn btn-default" href="products/${row.id}"><i class="bi bi-eye"></i></a>
    <a class="btn btn-primary my-2" href="products/${row.id}/edit"><i class="bi bi-pen"></i></a>
    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">Eliminazione</h5>
                </div>
                <div class="modal-body text-center">
                    <p>Sei sicuro di voler procedere con la eliminazine ?</p>
                    <div class="spinner-border d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-danger">Elimina</button>
                </div>
            </div>
        </div>
    </div>
</div>`;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Italian.json"
            },
            "columnDefs": [
                {"orderable": false, "targets": 4}
            ],
            "order": [[0, "desc"]]
        });

        table.on('draw.dt', function (a, b, rows) {
            $('div.actions button.btn-danger:not(div.modal button.btn-danger)').on('click', openFormElimina);

            $('div.actions div.modal button.btn-danger').each(function (index, element) {
                handleButtonClick(index, element, table.data().toArray());
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
                    url: 'api/products/' + id,
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
    });
} else if ($("#container-prodotto").length > 0) {
    // EDIT e CREATE
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
            if (prodotto) {
                url = URL_BASE_API + '/products/' + $("input[name='id_prodotto']").val() + "";
                method = 'put';
            } else {
                url = URL_BASE_API + '/products';
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
            });
        });
    });

    function recuperoInfoProdotto() {
        var ordine = null;
        $.ajax({
            async: false,
            url: URL_BASE_API + '/products/' + $("input[name='id_prodotto']").val(),
            method: 'get',
            success: function (response) {
                if (response.esito) {
                    ordine = response.object;
                }
            }
        });
        return ordine;
    }
} else {
    // SHOW
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

}
