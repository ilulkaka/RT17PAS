$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    getNikCasting2();
    getNoCast();

    $("#btn_reload").on("click", function() {
        getListKomposisi();
    });

    $("#fek_fil").val('add');
    $("#frm_fek").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "POST",
            url: APP_BACKEND + "foundry/ins_komposisi",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    formReset();
                    listKomposisi.ajax.reload(null, false);
                    $("#btn_submit").prop("disabled", false).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $('#tb_komposisi').on('click', '.editKomp', function() {
        var row = $(this).closest('tr');
        var datas = listKomposisi.row(row).data();

        if ($("#select_nik_casting option[value='" + datas.opr_qc + "']").length === 0) {
            // Tambahkan opsi baru jika belum ada
            var newOption = new Option(datas.opr_qc, datas.opr_qc, true, true);
            $("#select_nik_casting").append(newOption).trigger('change');
        } else {
            $("#select_nik_casting").val(datas.opr_qc).trigger('change');
        }

        if ($("#select_nik_casting2 option[value='" + datas.opr_melting + "']").length === 0) {
            var newOption = new Option(datas.opr_melting, datas.opr_melting, true, true);
            $("#select_nik_casting2").append(newOption).trigger('change');
        } else {
            $("#select_nik_casting2").val(datas.opr_melting).trigger('change');
        }

        if ($("#fek_castNo option[value='" + datas.cast_no + "']").length === 0) {
            var newOption = new Option(datas.cast_no, datas.cast_no, true, true);
            $("#fek_castNo").append(newOption).trigger('change');
        } else {
            $("#fek_castNo").val(datas.cast_no).trigger('change');
        }

        $('#fek_castNo').prop('disabled', true).trigger('change');

        $("#fek_id").val(datas.id_komposisi);
        $("#fek_tglCek").val(datas.tgl_cek);
        $("#fek_c").val(parseFloat(datas.c).toFixed(4));
        $("#fek_si").val(parseFloat(datas.si).toFixed(4));
        $("#fek_mn").val(parseFloat(datas.mn).toFixed(4));
        $("#fek_p").val(parseFloat(datas.p).toFixed(4));
        $("#fek_s").val(parseFloat(datas.s).toFixed(4));
        $("#fek_b").val(parseFloat(datas.b).toFixed(4));
        $("#fek_cu").val(parseFloat(datas.cu).toFixed(4));
        $("#fek_sn").val(parseFloat(datas.sn).toFixed(4));
        $("#fek_ni").val(parseFloat(datas.ni).toFixed(4));
        $("#fek_cr").val(parseFloat(datas.cr).toFixed(4));

        $("#btn_save").html('Update ');
        $("#fek_fil").val('upd');
    });

    $('#tb_komposisi').on('click', '.delKomp', function() {
        var row = $(this).closest('tr');
        var datas = listKomposisi.row(row).data();

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
                    url: APP_BACKEND + "foundry/del_komposisi/" + datas.id_komposisi,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: "json",
                })
                .done(function(resp) {
                    if (resp.success) {
                        fireAlert('success', resp.message);
                        listKomposisi.ajax.reload(null, false);
                    } else {
                        infoFireAlert('error', resp.message);
                    }
                })
                .fail(function(xhr) {
                    if (xhr.status === 403) {
                        infoFireAlert("error", "Anda tidak memiliki izin untuk Hapus data ini.");
                    } else {
                        infoFireAlert("error", "Terjadi kesalahan: " + xhr.responseJSON.message);
                    }
                });
            } else {
                console.log("❌ User pilih No, tidak ada perubahan.");
            }
        })
    });

});

function getNikCasting2 (){
    $.ajax({
        url: APP_BACKEND + 'foundry/get_nik_casting',
        type: 'GET',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: 'json',
    })

    .done(function(resp) {
        if (resp.success) {
            const selectNikCasting = $('#select_nik_casting2');
            selectNikCasting.empty();
            selectNikCasting.append('<option value="">Pilih NIK...</option>');

            resp.datas.forEach(function (nik) {
                selectNikCasting.append('<option value="' + nik.nik + '">' + nik.nik + " / "+ nik.nama + '</option>');
            });

            selectNikCasting.on('change', function () {
                var selectNikCasting = $(this).val();
                var selectedData = resp.datas.find(nik => nik.nik === selectNikCasting);
            });

        } else {
            $("#error").html("<div class='alert alert-danger'><div>Error</div></div>");
        }

    })
    .fail(function() {
        $("#error").html(
            "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
        );
    });
}

function getNoCast (){
    $.ajax({
        url: APP_BACKEND + 'foundry/get_no_cast',
        type: 'GET',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: 'json',
    })

    .done(function(resp) {
        if (resp.success) {
            const selectNoCast = $('#fek_castNo');
            selectNoCast.empty();
            selectNoCast.append('<option value="">Pilih Nomer Cast...</option>');

            resp.datas.forEach(function (noCast) {
                selectNoCast.append('<option value="' + noCast.cast_no + '">' + noCast.cast_no + '</option>');
            });

            selectNoCast.on('change', function () {
                var selectNoCast = $(this).val();
                var selectedData = resp.datas.find(noCast => noCast.cast_no === selectNoCast);
            });

        } else {
            $("#error").html("<div class='alert alert-danger'><div>Error</div></div>");
        }

    })
    .fail(function() {
        $("#error").html(
            "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
        );
    });
}

var listKomposisi;
function getListKomposisi (){
    if ($.fn.DataTable.isDataTable('#tb_komposisi')) {
        listKomposisi.ajax.reload();
    } else {
        listKomposisi = $('#tb_komposisi').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'foundry/list_komposisi',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.tgl_awal = $("#tgl_awal").val();
                    d.tgl_akhir = $("#tgl_akhir").val();
                    d.lotNo = $("#fek_lotNo").val();
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
                    targets: [16],
                    data: null,
                    render: function(data, type, row, meta) {
                        return "<a href = '#' style='font-size:14px' class = 'editKomp'> Edit </a> || <a href = '#' style='font-size:14px' class ='delKomp' > Delete </a>";

                    }
                }

            ],

            columns: [{
                    data: 'id_komposisi',
                    name: 'id_komposisi'
                },
                {
                    data: 'tgl_cek',
                    name: 'tgl_cek'
                },
                {
                    data: 'opr_qc',
                    name: 'opr_qc'
                },
                {
                    data: 'opr_melting',
                    name: 'opr_melting'
                },
                {
                    data: 'lot_no',
                    name: 'lot_no'
                },
                {
                    data: 'cast_no',
                    name: 'cast_no'
                },
                {
                    data: 'c',
                    name: 'c',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 'si',
                    name: 'si',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 'mn',
                    name: 'mn',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 'p',
                    name: 'p',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 's',
                    name: 's',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 'b',
                    name: 'b',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 'cu',
                    name: 'cu',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 'sn',
                    name: 'sn',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 'ni',
                    name: 'ni',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
                {
                    data: 'cr',
                    name: 'cr',
                    className: 'text-center',
                    render: $.fn.dataTable.render.number(',', '.', 4, '')
                },
            ],
        });
    }
}

function formReset (){
    $("#frm_fek").trigger('reset');
    $("#select_nik_casting").val(null).trigger('change');
    $("#select_nik_casting2").val(null).trigger('change');
    $("#fek_castNo").val(null).trigger('change');
}