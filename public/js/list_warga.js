$(document).ready(function() {

    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    document.getElementById('blok').addEventListener('input', function (e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (value.length > 2) {
            value = value.slice(0, 3) + '/' + value.slice(3);
        }
        e.target.value = value;
    });

    $("#btn_tambah").on("click", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modal_tw'));
        myModal.show();
    });

    getListWarga();

    $("#btn_reload").on("click", function() {
        getListWarga();
    });

    $("#btn_close").on("click", function() {
        location.reload();
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

    $("#tb_list_warga").on('click', '.edtWarga', function(e) {
        e.preventDefault();
        var datas = listWarga.row($(this).parents('tr')).data();

        $("#e_id_warga").val(datas.id_warga);
        $("#e_no_kk").val(datas.no_kk);
        $("#e_no_ktp").val(datas.no_ktp);
        $("#e_nama").val(datas.nama);
        $("#e_alamat").val(datas.alamat_ktp);
        $("#e_blok").val(datas.blok);
        $("#e_jk").val(datas.jenis_kelamin);
        $("#e_status_tinggal").val(datas.status_tinggal);
        $("#e_no_telp").val(datas.no_telp);
        $("#e_keterangan").val(datas.keterangan);

        $("#modal_ew").modal("show");
    });

    $("#frm_edw").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#e_btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "PATCH",
            url: APP_BACKEND + "datas/edt_warga",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    $("#modal_ew").modal("toggle");
                    listWarga.ajax.reload(null, false);
                    $("#e_btn_submit").prop("disabled", false).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $("#tb_list_warga").on('click', '.delWarga', function(e) {
        e.preventDefault();
        var datas = listWarga.row($(this).parents('tr')).data();

        infoFireAlert("info", "Under Development");
    });

});


var listWarga;
function getListWarga (){
    if ($.fn.DataTable.isDataTable('#tb_list_warga')) {
        listWarga.ajax.reload();
    } else {
        listWarga = $('#tb_list_warga').DataTable({
            responsive: true,
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
                    targets: [10],
                    data: null,
                    render: function(data, type, row, meta) {
                        return "<a href = '#' style='font-size:14px' class = 'edtWarga'> Edit </a> || <a href = '#' style='font-size:14px' class ='delWarga' > Delete </a>";

                    }
                }

            ],

            columns: [{
                    data: 'id_warga',
                    name: 'id_warga',
                },
                {
                    data: 'no_kk',
                    name: 'no_kk',
                },
                {
                    data: 'no_ktp',
                    name: 'no_ktp',
                },
                {
                    data: 'nama',
                    name: 'nama',
                },
                {
                    data: 'alamat_ktp',
                    name: 'alamat_ktp',
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
                {
                    data: 'keterangan',
                    name: 'keterangan',
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