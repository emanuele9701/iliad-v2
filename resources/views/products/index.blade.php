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
    <script>
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
                language: {
                    url: "{{asset('i18n-datatables-it.json')}}"
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
    </script>
@endsection

@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css" rel="stylesheet">
    <style>
        .dt-search {
            display: inline;
        }
    </style>
@endsection
