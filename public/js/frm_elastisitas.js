$(document).ready(function() {
    
    get_list_elastisitas();
    $("#btn_reload").on("click", function() {
        get_list_elastisitas();
    });

    $('#tb_elastisitas').on('click', '.updMoh', function() {
        var datas = listElastisitas.row($(this).parents('tr')).data();
        $("#um_part_no").val(datas.part_no);
        $("#modal_update_mohan").modal('show');
        $("#um_camu").focus();

        $("#btn_submit").prop("disabled", false).text(" Insert ");
    });

    $("#frm_um").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "POST",
            url: APP_BACKEND + "foundry/ins_mohan",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    listElastisitas.ajax.reload(null, false);
                    $('#modal_update_mohan').modal('toggle');
                    $("#btn_submit").prop("disabled", false);
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $('#tb_elastisitas').on('click', '.updHard', function() {
        $("#frm_uh").trigger('reset');

        var datas = listElastisitas.row($(this).parents('tr')).data();
        $("#uh_id").val(datas.id_elastisitas);
        $("#uh_part_no").val(datas.part_no);
        $("#uh_lot_no").val(datas.lot_no);
        $("#modal_update_hardness").modal('show');

        $("#uh_h1").focus();
        $("#btn_submit").prop("disabled", false).text("Update");
    });

    $("#frm_uh").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "PATCH",
            url: APP_BACKEND + "foundry/upd_hardness",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    listElastisitas.ajax.reload(null, false);
                    $('#modal_update_hardness').modal('toggle');
                    $("#btn_submit").prop("disabled", false);
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $('#tb_elastisitas').on('click', '.edtHard', function() {
        var datas = listElastisitas.row($(this).parents('tr')).data();
        $("#eh_id").val(datas.id_elastisitas);
        $("#eh_part_no").val(datas.part_no);
        $("#eh_lot_no").val(datas.lot_no);
        $("#eh_h1").val(datas.hard_1);
        $("#eh_h2").val(datas.hard_2);
        $("#eh_h3").val(datas.hard_3);
        $("#eh_h4").val(datas.hard_4);
        $("#modal_edit_hardness").modal('show');

        $("#btn_submit").prop("disabled", false).text("Update");
    });

    $("#frm_eh").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "PATCH",
            url: APP_BACKEND + "foundry/edt_hardness",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    listElastisitas.ajax.reload(null, false);
                    $('#modal_edit_hardness').modal('toggle');
                    $("#btn_submit").prop("disabled", false);
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $('#tb_elastisitas').on('click', '.updElas', function() {
        var datas = listElastisitas.row($(this).parents('tr')).data();

        if (datas.m_camu === null || datas.m_camu === '') {
            alert('Ukuran Mohan tidak ada, Update ukuran Mohan .')
        } else {
            $("#frm_ue").trigger('reset');
            $("#ue_b1").trigger('focus');

            $("#modal_update_elastisitas").modal('show');
            $("#ue_id").val(datas.id_elastisitas);
            $("#ue_part_no").val(datas.part_no);
            $("#ue_lot_no").val(datas.lot_no);
            $("#ue_diameter").val(datas.m_camu);
        }
    });

    $('#modal_update_elastisitas').on('shown.bs.modal', function () {
        $("#ue_b1").focus();
    });

    $("#ue_s").on("keypress", function(e) {
        if (e.key === 'Enter') {
            var b1 = $("#ue_b1").val();
            var b2 = $("#ue_b2").val();
            var b3 = $("#ue_b3").val();
            var b4 = $("#ue_b4").val();
            var b5 = $("#ue_b5").val();

            var t1 = $("#ue_t1").val();
            var t2 = $("#ue_t2").val();
            var t3 = $("#ue_t3").val();
            var t4 = $("#ue_t4").val();
            var t5 = $("#ue_t5").val();

            var w1 = $("#ue_w1").val();
            var w2 = $("#ue_w2").val();
            var w3 = $("#ue_w3").val();
            var w4 = $("#ue_w4").val();
            var w5 = $("#ue_w5").val();

            var diameter = $("#ue_diameter").val();
            var s = $("#ue_s").val();

            var e1 = ((diameter / t1 - 1) * (diameter / t1 - 1) * (diameter / t1 - 1) *
                    14.14 * w1) /
                b1 / s;
            var e2 = ((diameter / t2 - 1) * (diameter / t2 - 1) * (diameter / t2 - 1) *
                    14.14 * w2) /
                b2 / s;
            var e3 = ((diameter / t3 - 1) * (diameter / t3 - 1) * (diameter / t3 - 1) *
                    14.14 * w3) /
                b3 / s;
            var e4 = ((diameter / t4 - 1) * (diameter / t4 - 1) * (diameter / t4 - 1) *
                    14.14 * w4) /
                b4 / s;
            var e5 = ((diameter / t5 - 1) * (diameter / t5 - 1) * (diameter / t5 - 1) *
                    14.14 * w5) /
                b5 / s;
            var avg_e = (e1 + e2 + e3 + e4 + e5) / 5;

            var formatted_e1 = Math.round(e1);
            var formatted_e2 = Math.round(e2);
            var formatted_e3 = Math.round(e3);
            var formatted_e4 = Math.round(e4);
            var formatted_e5 = Math.round(e5);

            var formatted_avg = Math.round(avg_e);
            $("#ue_e1").val(formatted_e1);
            $("#ue_e2").val(formatted_e2);
            $("#ue_e3").val(formatted_e3);
            $("#ue_e4").val(formatted_e4);
            $("#ue_e5").val(formatted_e5);
            $("#ue_avg").val(formatted_avg);
        }
    });

    $("#frm_ue").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "PATCH",
            url: APP_BACKEND + "foundry/upd_elastisitas",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    listElastisitas.ajax.reload(null, false);
                    $('#modal_update_elastisitas').modal('toggle');
                    $("#btn_submit").prop("disabled", false);
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $('#tb_elastisitas').on('click', '.edtElas', function() {
        var datas = listElastisitas.row($(this).parents('tr')).data();
        $("#ee_id").val(datas.id_elastisitas);
        $("#ee_part_no").val(datas.part_no);
        $("#ee_lot_no").val(datas.lot_no);
        $("#ee_diameter").val(datas.m_camu);

        $("#ee_b1").val(datas.b1);
        $("#ee_b2").val(datas.b2);
        $("#ee_b3").val(datas.b3);
        $("#ee_b4").val(datas.b4);
        $("#ee_b5").val(datas.b5);

        $("#ee_t1").val(datas.t1);
        $("#ee_t2").val(datas.t2);
        $("#ee_t3").val(datas.t3);
        $("#ee_t4").val(datas.t4);
        $("#ee_t5").val(datas.t5);

        $("#ee_w1").val(datas.w1);
        $("#ee_w2").val(datas.w2);
        $("#ee_w3").val(datas.w3);
        $("#ee_w4").val(datas.w4);
        $("#ee_w5").val(datas.w5);

        $("#ee_e1").val(datas.e1);
        $("#ee_e2").val(datas.e2);
        $("#ee_e3").val(datas.e3);
        $("#ee_e4").val(datas.e4);
        $("#ee_e5").val(datas.e5);


        $("#ee_s").val(datas.s);
        $("#ee_kak1").val(datas.ka_1);
        $("#ee_kak2").val(datas.ka_2);
        $("#ee_avg").val(datas.avg_e);

        $("#modal_edit_elastisitas").modal('show');
    });

    $("#ee_s").on("keypress",function(e) {
        if (e.key === 'Enter') {
            edtAvgElastisitas();
        }
    })

    $("#frm_ee").on("submit", function(e) {
        e.preventDefault();
        edtAvgElastisitas();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "PATCH",
            url: APP_BACKEND + "foundry/edt_elastisitas",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    listElastisitas.ajax.reload(null, false);
                    $('#modal_edit_elastisitas').modal('toggle');
                    $("#btn_submit").prop("disabled", false);
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $('#tb_elastisitas').on('click', '.btn-hapus', function() {
        var datas = listElastisitas.row($(this).parents('tr')).data();

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
                    url: APP_BACKEND + "foundry/del_elastisitas/" + datas.id_elastisitas,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: "json",
                })
                .done(function(resp) {
                    if (resp.success) {
                        fireAlert('success', resp.message);
                        listElastisitas.ajax.reload(null, false);
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


var listElastisitas;
function get_list_elastisitas (){
    if ($.fn.DataTable.isDataTable('#tb_elastisitas')) {
        listElastisitas.ajax.reload();
    } else {
        listElastisitas = $('#tb_elastisitas').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'foundry/list_elastisitas',
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
                    targets: [25],
                    data: null,
                    render: function(data, type, row, meta) {
                        if (data.hard_1 == null && data.avg_e == null) {
                            return "<a href = '#' style='font-size:14px' class = 'updHard'> Upd Hardness </a> || <a href = '#' style='font-size:14px' class ='updElas' > Upd Elastisitas </a>";
                        } else if (data.hard_1 != null && data.avg_e == null) {
                            return "<a href = '#' style='font-size:14px; color:red' class = 'edtHard'> Edit Hardness </a> || <a href = '#' style='font-size:14px' class ='updElas' > Upd Elastisitas </a>";
                        } else if (data.hard_1 == null && data.avg_e != null) {
                            return "<a href = '#' style='font-size:14px' class = 'updHard'> Upd Hardness </a> || <a href = '#' style='font-size:14px; color:red' class ='edtElas' > Edit Elastisitas </a>";
                        } else {
                            return "<a href = '#' style='font-size:14px; color:red' class = 'edtHard'> Edit Hardness </a> || <a href = '#' style='font-size:14px; color:red' class ='edtElas' > Edit Elastisitas </a>";
                        }
                    }
                },
                {
                    targets: [26],
                    data: null,
                    render: function(data, type, row, meta) {
                        return "<button class='btn btn-outline btn-hapus btn-flat btn-sm' id='btn_del' name='btn_del'>Deleted</button>";
                    }
                }

            ],

            columns: [{
                    data: 'id_elastisitas',
                    name: 'id_elastisitas'
                },
                {
                    data: 'barcode_no',
                    name: 'barcode_no',
                    className: 'text-center',
                },
                {
                    data: 'tgl_proses',
                    name: 'tgl_proses'
                },
                {
                    data: 'leadle_no',
                    name: 'leadle_no',
                    className: 'text-center',
                    // render: $.fn.dataTable.render.number(',', '.', 0, '')
                },
                {
                    data: 'pemeriksa',
                    name: 'pemeriksa'
                },
                {
                    data: 'ketetapan',
                    name: 'ketetapan'
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
                    data: 'm_camu',
                    name: 'm_camu',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data === null || data === '') {
                            return "<a href = '#' style='font-size:14px' class = 'updMoh '><u> Upd Mohan </u></a>";
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: 'e1',
                    name: 'e1'
                },
                {
                    data: 'e2',
                    name: 'e2'
                },
                {
                    data: 'e3',
                    name: 'e3'
                },
                {
                    data: 'e4',
                    name: 'e4'
                },
                {
                    data: 'e5',
                    name: 'e5'
                },
                {
                    data: 'avg_e',
                    name: 'avg_e'
                },
                {
                    data: 'hard_1',
                    name: 'hard_1'
                },
                {
                    data: 'hard_2',
                    name: 'hard_2'
                },
                {
                    data: 'hard_3',
                    name: 'hard_3'
                },
                {
                    data: 'hard_4',
                    name: 'hard_4'
                },
                {
                    data: 'mikrostruktur',
                    name: 'mikrostruktur'
                },
                {
                    data: 'ka_1',
                    name: 'ka_1'
                },
                {
                    data: 'ka_2',
                    name: 'ka_2'
                },
                {
                    data: 'ringkasan',
                    name: 'ringkasan'
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
            ],
        });
    }
}

function edtAvgElastisitas (){
    var b1 = $("#ee_b1").val();
            var b2 = $("#ee_b2").val();
            var b3 = $("#ee_b3").val();
            var b4 = $("#ee_b4").val();
            var b5 = $("#ee_b5").val();

            var t1 = $("#ee_t1").val();
            var t2 = $("#ee_t2").val();
            var t3 = $("#ee_t3").val();
            var t4 = $("#ee_t4").val();
            var t5 = $("#ee_t5").val();

            var w1 = $("#ee_w1").val();
            var w2 = $("#ee_w2").val();
            var w3 = $("#ee_w3").val();
            var w4 = $("#ee_w4").val();
            var w5 = $("#ee_w5").val();

            var diameter = $("#ee_diameter").val();
            var s = $("#ee_s").val();

            var e1 = ((diameter / t1 - 1) * (diameter / t1 - 1) * (diameter / t1 - 1) *
                    14.14 * w1) /
                b1 / s;
            var e2 = ((diameter / t2 - 1) * (diameter / t2 - 1) * (diameter / t2 - 1) *
                    14.14 * w2) /
                b2 / s;
            var e3 = ((diameter / t3 - 1) * (diameter / t3 - 1) * (diameter / t3 - 1) *
                    14.14 * w3) /
                b3 / s;
            var e4 = ((diameter / t4 - 1) * (diameter / t4 - 1) * (diameter / t4 - 1) *
                    14.14 * w4) /
                b4 / s;
            var e5 = ((diameter / t5 - 1) * (diameter / t5 - 1) * (diameter / t5 - 1) *
                    14.14 * w5) /
                b5 / s;
            var avg_e = (e1 + e2 + e3 + e4 + e5) / 5;

            var formatted_e1 = Math.round(e1);
            var formatted_e2 = Math.round(e2);
            var formatted_e3 = Math.round(e3);
            var formatted_e4 = Math.round(e4);
            var formatted_e5 = Math.round(e5);

            var formatted_avg = Math.round(avg_e);
            $("#ee_e1").val(formatted_e1);
            $("#ee_e2").val(formatted_e2);
            $("#ee_e3").val(formatted_e3);
            $("#ee_e4").val(formatted_e4);
            $("#ee_e5").val(formatted_e5);
            $("#ee_avg").val(formatted_avg);
}