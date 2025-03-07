$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    $("#select_nik_casting").select2('focus');

    $("#btn_reload").on("click", function() {
        get_list_pouring();
    });

    $("#fehp_sb").on("keypress", function(e) {
        var barcode_no = $("#fehp_sb").val();
        console.log(typeof jQuery);
        if (e.key === 'Enter') {
            $.ajax({
                    type: "GET",
                    url: APP_BACKEND + "foundry/get_barcode_pouring",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    data: {
                        "barcode_no": barcode_no,
                    },
                    dataType: "json",
                })
                .done(function(resp) {
                    if (resp.success == true) {
                        $("#fehp_part").val(resp.datas[0].part_no);
                        $("#fehp_lot").val(resp.datas[0].lot_no);
                        $("#fehp_nocas").focus();
                    } else {
                        fireAlert("error", resp.message);
                        $("#fehp_part").val('');
                        $("#fehp_lot").val('');
                        $("#fehp_nocas").val('');
                        $("#fehp_sb").focus();
                    }
                })
                .fail(function() {
                    $("#error").html(
                        "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                    );
                });
        }
    });

    $("#frm_fehp").on("submit", function(e){
        e.preventDefault();
        var datas = $("#frm_fehp").serializeArray();

        $.ajax({
            type: "POST",
            url: APP_BACKEND + "foundry/ins_pouring",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
            dataType: "json",
        })
        .done(function(resp) {
            if (resp.success) {
                fireAlert("success", resp.message);
                location.reload();
                $("#select_nik_casting").select2('focus');
            } else {
                fireAlert("error", resp.message);
            }
        })
    });

    $('#tb_hasil_pouring').on('click', '.insertPou', function() {
        var datas = listPouring.row($(this).parents('tr')).data();
        $("#im_id").val(datas.id_hasil_pouring);
        $("#im_scan_barcode").val(datas.barcode_no);
        $("#im_tgl_proses").val(datas.tgl_proses);
        $("#im_opr").val(datas.opr_name);
        $("#im_ringkasan").val(datas.keterangan);
        $("#im_part_no").val(datas.part_no);
        $("#im_lot_no").val(datas.lot_no);
        $("#im_cast_no").val(datas.cast_no);
        $("#im_leadle_no").val(datas.leadle_no);
        $("#im_keterangan").val(datas.keterangan_1);
        $("#modal_insert_mikrostruktur").modal('show');
        im_awal();
        $("#btn_submit").prop("disabled", false).text(" Insert ");
    });

    $("#frm_im").on("submit", function(e){
        e.preventDefault();
        var datas = $("#frm_im").serializeArray();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "POST",
            url: APP_BACKEND + "foundry/ins_mikrostruktur",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
        .done(function(resp) {
            if (resp.success) {
                fireAlert("success", resp.message);
                $('#modal_insert_mikrostruktur').modal('toggle');
                listPouring.ajax.reload(null, false);
                $("#btn_submit").prop("disabled", false);
            } else {
                infoFireAlert("error", resp.message);
            }
        })
    });

    $('#tb_hasil_pouring').on('click', '.editPou', function() {
        var datas = listPouring.row($(this).parents('tr')).data();
        $("#ed_id_pouring").val(datas.id_hasil_pouring);
        $("#ed_partno").val(datas.part_no);
        $("#ed_lotno").val(datas.lot_no);
        $("#ed_castno").val(datas.cast_no);
        $("#ed_leadleno").val(datas.leadle_no);
        $("#ed_mesinno").val(datas.mesin_no);
        $("#ed_yama").val(datas.yama);
        $("#ed_defect").val(datas.defect);
        $("#ed_keterangan").val(datas.keterangan);
        $("#ed_keterangan1").val(datas.keterangan_1);
        $("#modal_edit_pouring").modal('show');

        $("#btn_submit").prop("disabled", false).text("Simpan");
    });

    $("#frm_ep").on("submit",function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
                type: "PATCH",
                url: APP_BACKEND + "foundry/edt_pouring",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: datas,
            })
            .done(function(resp) {
                if (resp.success) {
                    $('#modal_edit_pouring').modal('toggle');
                    fireAlert("success", resp.message);
                    listPouring.ajax.reload(null, false);
                    $("#btn_submit").prop("disabled", false).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $('#tb_hasil_pouring').on('click', '.delPou', function(e) {
        e.preventDefault();
        var datas = listPouring.row($(this).parents('tr')).data();
        
        Swal.fire({
            title: "Konfirmasi",
            text: "do you want to delete this record ?",
            type: 'question',
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "delete",
                    url: APP_BACKEND + "foundry/del_hasil_pouring/" + datas.id_hasil_pouring + "/" + datas.lot_no,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: "json",
                })
                .done(function(resp) {
                    if (resp.success) {
                        fireAlert('success', resp.message);
                        listPouring.ajax.reload(null, false);
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

    $("#btn_excel").on("click", function() {
        var tgl_awal = $('#tgl_awal').val();
        var tgl_akhir = $("#tgl_akhir").val();

        Swal.fire({
            title: "Konfirmasi",
            text: "Download Hasil Pouring periode " + tgl_awal + "  Sampai  " + tgl_akhir + " ?",
            type: 'question',
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "get",
                    url: APP_BACKEND + "foundry/xls_pouring",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: "json",
                    data: {
                        "tgl_awal": tgl_awal,
                        "tgl_akhir": tgl_akhir,
                    },
                })
                .done(function(resp) {
                    if (resp.success) {
                        var fpath = resp.file;
                        window.open(fpath, '_blank');
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

var listPouring;
function get_list_pouring (){
    if ($.fn.DataTable.isDataTable('#tb_hasil_pouring')) {
        listPouring.ajax.reload();
    } else {
            listPouring = $('#tb_hasil_pouring').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            // responsive: true,
            ajax: {
                url: APP_BACKEND + 'foundry/list_pouring',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.tgl_awal = $("#tgl_awal").val();
                    d.tgl_akhir = $("#tgl_akhir").val();
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
                    targets: [1],
                    data: null,
                    render: function(data, type, row, meta) {
                        if (data == null) {
                            return "<a href = '#' style='font-size:14px' class = 'insertPou'> Insert </a>";
                        } else {
                            return "";
                        }
                    }
                },
                {
                    targets: [14],
                    data: null,
                    render: function(data, type, row, meta) {
                        if (data.bbar == null || data.bbar == '') {
                            return "<a href = '#' style='font-size:14px' class = 'editPou'> Edit </a> || <a href = '#' style='font-size:14px' class ='delPou' > Delete </a>";
                        } else {
                            return "";
                        }
                    }
                }

            ],

            columns: [{
                    data: 'id_hasil_pouring',
                    name: 'id_hasil_pouring'
                },
                {
                    data: 'bbar',
                    name: 'bbar'
                },
                {
                    data: 'barcode_no',
                    name: 'barcode_no'
                },
                {
                    data: 'tgl_proses',
                    name: 'tgl_proses'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {

                        var date = new Date(data);
                        var hours = ("0" + date.getHours()).slice(-2);
                        var minutes = ("0" + date.getMinutes()).slice(-2);
                        var seconds = ("0" + date.getSeconds()).slice(-2);
                        return hours + ":" + minutes + ":" + seconds;
                    }
                },
                {
                    data: 'part_no',
                    name: 'part_no'
                },
                {
                    data: 'lot_no',
                    name: 'lot_no',
                    //     render: $.fn.dataTable.render.number(',', '.', 0, '')
                },
                {
                    data: 'cast_no',
                    name: 'cast_no',
                    className: 'text-center'
                },
                {
                    data: 'leadle_no',
                    name: 'leadle_no',
                    className: 'text-center',
                    // render: $.fn.dataTable.render.number(',', '.', 0, '')
                },
                {
                    data: 'mesin_no',
                    name: 'mesin_no',
                    className: 'text-center'
                },
                {
                    data: 'yama',
                    name: 'yama',
                    className: 'text-center',
                },
                {
                    data: 'defect',
                    name: 'defect',
                    className: 'text-center',
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).css('color',
                            'red'
                        ); // Mengubah warna teks menjadi merah pada kolom defect
                        $(td).css('font-weight',
                            'bold'); // Mengubah teks menjadi Bold pada kolom defect
                    }
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'keterangan_1',
                    name: 'keterangan_1'
                },
            ],
        });
    }
}

function im_awal() {
    $("#im_mikrostruktur").val('');
    setTimeout(function() {
        $("#im_mikrostruktur").focus();
    }, 100);

    $("#im_ketetapan").val('');
}