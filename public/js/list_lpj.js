$(document).ready(function() {

    getListLpj();

    $("#btn_reload").on("click", function() {
        getListLpj();
    });

    $("#btn_pdf").on("click", function() {
        var tgl_awal = $("#tgl_awal").val();
        var tgl_akhir = $("#tgl_akhir").val();
        window.open(APP_URL + "/keuangan/rpt/cetak_lpj/"+tgl_awal+"/"+tgl_akhir, "_blank");
    });

});

var listLpj;
function getListLpj (){
    if ($.fn.DataTable.isDataTable('#tb_list_lpj')) {
        listLpj.ajax.reload();
    } else {
        listLpj = $('#tb_list_lpj').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'keuangan/list_lpj',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.tgl_awal = $("#tgl_awal").val();
                    d.tgl_akhir = $("#tgl_akhir").val();
                    d.jenis = $("#jenis").val();
                },
                complete: function() {
                    $("#btn_reload").attr("disabled", false);
                },
                error: function() {
                    $("#btn_reload").attr("disabled", false);
                }
            },
            columnDefs: [{
                    targets: [0],
                    visible: false,
                    searchable: false
                },
                { targets: 4, className: 'text-right' }
            ],

            columns: [{
                    data: 'id_lpj',
                    name: 'id_lpj',
                },
                {
                    data: 'tgl_transaksi',
                    name: 'tgl_transaksi',
                    className: 'text-left',
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi',
                },
                {
                    data: 'jenis',
                    name: 'jenis',
                    className: 'text-left',
                },
                {
                    data: 'nominal',
                    name: 'nominal',
                    className: 'text-left',
                    render: function(data, type, row) {
                        return 'Rp ' + parseFloat(data).toLocaleString('id-ID', { 
                            minimumFractionDigits: 2, 
                            maximumFractionDigits: 2 
                        });
                    }
                },
                {
                    data: 'pic',
                    name: 'pic',
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                },           
            ],
        });
    }
}