$(document).ready(function() {

    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    getListWarga();

    $("#btn_reload").on("click", function() {
        getListWarga();
    });

    $("#frm_fedw").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "POST",
            url: APP_BACKEND + "datas/ins_warga",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    formReset();
                    listWarga.ajax.reload(null, false);
                    $("#btn_submit").prop("disabled", false).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

});


var listWarga;
function getListWarga (){
    if ($.fn.DataTable.isDataTable('#tb_list_warga')) {
        listWarga.ajax.reload();
    } else {
        listWarga = $('#tb_list_warga').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'datas/list_warga',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    // d.tgl_awal = $("#tgl_awal").val();
                    // d.tgl_akhir = $("#tgl_akhir").val();
                    d.status_tinggal = $("#status_tinggal").val();
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
                    targets: [6],
                    data: null,
                    render: function(data, type, row, meta) {
                        return "<a href = '#' style='font-size:14px' class = 'editKomp'> Edit </a> || <a href = '#' style='font-size:14px' class ='delKomp' > Delete </a>";

                    }
                }

            ],

            columns: [{
                    data: 'id_warga',
                    name: 'id_warga',
                },
                {
                    data: 'nama',
                    name: 'nama',
                },
                {
                    data: 'blok',
                    name: 'blok',
                },
                {
                    data: 'jenis_kelamin',
                    name: 'jenis_kelamin',
                },
                {
                    data: 'status_tinggal',
                    name: 'status_tinggal',
                },
                {
                    data: 'no_telp',
                    name: 'no_telp'
                },                
            ],
        });
    }
}

function formReset (){
    $("#frm_fedw").trigger('reset');
    $("#jk").val(null).trigger('change');
    $("#status_tinggal").val(null).trigger('change');
}