<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app')

@section('title', 'Lista Ordini')
@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css" rel="stylesheet">
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
    <script>
        var table = $("#orders-table").DataTable({
            ajax: $("#orders-table").attr('data-url'),
            language: {
                url: "{{asset('i18n-datatables-it.json')}}"
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

    </script>
@endsection
