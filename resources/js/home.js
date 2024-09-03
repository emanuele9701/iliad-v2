var totaleOrdini = $("#totaleOrdini");
var ordiniOdierni = $("#ordiniOdierni");
var sumOrdini = $("#sumOrdini");


function elaboroStatistiche(response) {
    totaleOrdini.text(response.totale_ordini);
    ordiniOdierni.text(response.ordini_odierni);
    sumOrdini.html("&euro; "+response.somma);
}

function formatterActions(data,row) {
    var button = "<div class='d-flex'>";
    button += '<a class="btn btn-default" href="orders/'+row.id+'"><i class="bi bi-eye"></i></a>';
    button += '<a class="btn btn-warning" href="orders/'+row.id+'/edit"><i class="bi bi-pen"></i></a>';

    button += "</div>";
    return button;
}
$(document).ready(function () {
    // Recupero le statistiche
    $.ajax({
        url:'api/orders/stats',
        method: 'get',
        dataType: 'json',
        success: elaboroStatistiche
    });

    const datatables = new DataTable('#table' , {
        ajax: $("#table").attr('data-url'),
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.5/i18n/it-IT.json'
        },
        processing: true,
        serverSide: true,
        searching: false,
        columns: [
            {
                data: 'id',
                orderable: false,
                name: 'id',
            },
            {
                data: 'name',
                orderable: false,
                name: 'name',
            },
            {
                data: 'total_value',
                orderable: false,
                name: 'total_value',
                render: function (data,type,row) {
                    return "&euro; "+data;
                }
            },
            {
                data: 'order_date',
                orderable: false,
                name: 'order_date'
            }
        ]
    });

})
