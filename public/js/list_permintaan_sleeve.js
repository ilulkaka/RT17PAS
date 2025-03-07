$(document).ready(function(){

    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    get_list_permintaan_sleeve();

    $("#btn_reload").on("click", function() {
        get_list_permintaan_sleeve();
    });

    $("#tb_permintaan_sleeve").on("click", ".btn-cetak", function (){
        var datas = listPermintaanSleeve.row($(this).parents('tr')).data();

        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda akan Mencetak & memproses Item. " + datas.item +
                    " dengan Qty  " + datas.qty + " ?",
            type: 'question',
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "patch",
                    url: APP_BACKEND + "foundry/proses_permintaan_sleeve",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: "json",
                    data: {
                        "id": datas.id_permintaan_sleeve
                    },
                })
                .done(function(resp) {
                    if (resp.success) {
                        fireAlert('success', resp.message);
                        listPermintaanSleeve.ajax.reload(null, false);

                        window.open(APP_URL + "/produksi/frm_foundry/cetak_permintaan_sleeve/" + datas
                            .id_permintaan_sleeve);
                    } else {
                        infoFireAlert('error', resp.message);
                    }
                })
                .fail(function() {
                    $("#error").html(
                        "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                    );

                });
            } else {
                console.log("❌ User pilih No, tidak ada perubahan.");
            }
        })
    });

});

var listPermintaanSleeve;
function get_list_permintaan_sleeve (){
    if ($.fn.DataTable.isDataTable('#tb_permintaan_sleeve')) {
        listPermintaanSleeve.ajax.reload();
    } else {
        listPermintaanSleeve = $('#tb_permintaan_sleeve').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'foundry/list_permintaan_sleeve',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.tgl_awal = $("#tgl_awal").val();
                    d.tgl_akhir = $("#tgl_akhir").val();
                    d.status_permintaan = $("#status_permintaan").val();
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
                {
                    targets: [11],
                    data: null,
                    render: function(data, type, row, meta) {
                        if(data.status_ps == 'Open'){
                            return "<button type='button' class='btn btn-cetak btn-flat btn-sm'>Proses & Cetak</button>";
                        } else {
                            return '';
                        }
                    }
                }

            ],

            columns: [{
                    data: 'id_permintaan_sleeve',
                    name: 'id_permintaan_sleeve'
                },
                {
                    data: 'barcode_no',
                    name: 'barcode_no'
                },
                {
                    data: 'tgl_request',
                    name: 'tgl_request'
                },
                {
                    data: 'jenis',
                    name: 'jenis'
                },
                {
                    data: 'item',
                    name: 'item',
                    //     render: $.fn.dataTable.render.number(',', '.', 0, '')
                },
                {
                    data: 'qty',
                    name: 'qty',
                    className: 'text-center'
                },
                {
                    data: 'nouki',
                    name: 'nouki',
                    className: 'text-center',
                    // render: $.fn.dataTable.render.number(',', '.', 0, '')
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'pengirim',
                    name: 'pengirim'
                },
                {
                    data: 'qty_ok',
                    name: 'qty_ok'
                },
                {
                    data: 'tgl_kirim',
                    name: 'tgl_kirim'
                },
            ],
        });
    }
}