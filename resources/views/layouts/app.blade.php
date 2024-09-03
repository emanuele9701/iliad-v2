<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestione Ordini')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('link')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand ps-2" href="{{ route('home') }}">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="ordersDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Ordini
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="ordersDropdown">
                        <li><a class="dropdown-item" href="{{ route('orders.index') }}">Visualizza</a></li>
                        <li><a class="dropdown-item" href="{{ route('orders.create') }}">Aggiungi</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Prodotti
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                        <li><a class="dropdown-item" href="{{ route('products.index') }}">Visualizza</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.create') }}">Aggiungi</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
    <div class="modal fade" id="modalErrorSimple" tabindex="-1" role="dialog" aria-labelledby="modalErrorSimpleLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalErrorSimpleLabel"><i class="bi bi-exclamation-triangle-fill"
                                                                          style="color:red;"></i>Errore riscontrato</h5>
                </div>
                <div class="modal-body">
                    <p>Si è verificato un errore: <span id="errore"></span>, riprovare.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSuccessSimple" tabindex="-1" role="dialog"
         aria-labelledby="modalSuccessSimpleLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSuccessSimpleLabel"><i class="bi bi-check-square-fill"
                                                                            style="color:green;"></i>Operazione
                        completata</h5>
                </div>
                <div class="modal-body">
                    <p>L'operazione si è conclusa con successo.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js" crossorigin="anonymous"></script>
<script>
    const URL_BASE_API = "{{env('APP_URL')}}/api";

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

    function openFormElimina(element) {
        var parent = $(element.currentTarget).parent();
        $($(parent).find('#modalDelete')).modal('show');
    }
    function searchTextTable(element) {
        if (element.value.length > 2) {
            var index = $(element).parent().parent().index();

            table.column(index).search(element.value).draw();
        }
    }
    $(document).ready(function () {
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
    });
</script>
@yield('scripts')
</body>
</html>
