$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    bsCustomFileInput.init();

    $("#nomer_induk_mesin").prop('disabled', true);
    $("#s_schedule").prop('disabled', true);
    $("#btn_submit").prop('disabled', true);
    $("#r_mt, #r_o").on("change",function() {
        $("#nomer_induk_mesin").prop('disabled', false);
        $("#s_schedule").prop('disabled', false);
        $("#btn_submit").prop('disabled', false);
        if ($("#r_mt").is(":checked")) {
            $("#fil").val('MesinTahunan');
            $("#s_masalah").val('Laporan pemeriksaan mesin tahunan');
        } else {
            $("#fil").val('overhaul');
            $("#s_masalah").val('Overhaul');
        }
    });

    getListSchPemeriksaan();
    $("#btn_reload").on("click", function() {
        getListSchPemeriksaan();
    });

    getAprHasilPekerjaan();
    $("#btn_reload2").on("click", function() {
        getAprHasilPekerjaan();
    });

    $("#frm_sch").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        // var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "POST",
            url: APP_BACKEND + "mtc/ins_schedule",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: formData,
            contentType: false,
            processData: false,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    formReset();
                    listSchPemeriksaan.ajax.reload(null, false);
                    $("#btn_submit").prop("disabled", true).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

    $('#l_schPemeriksaan').on('click', '.a_lampiran', function() {
        var datas = listSchPemeriksaan.row($(this).parents('tr')).data();

        $("#ud_id").val(datas.id_sch_mesin_tahunan);
        $("#ud_idPerbaikan").val(datas.id_perbaikan);
        $("#ud_status").val(datas.status);
        $('#modal_upload_document').modal('show');

        var placeholderText = $("#tt").attr("placeholder");
        // Mengatur isi elemen dengan nilai placeholder
        $("#tt").html(placeholderText);
        $("#file_ud_upload").val('');
    });

    $("#frm_ud").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
                type: "POST",
                url: APP_BACKEND + "mtc/upl_lampiran",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
            })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    $('#modal_upload_document').modal('toggle');
                    listSchPemeriksaan.ajax.reload(null, false);
                    $("#btn_submit").prop("disabled", true).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
            .fail(function() {
                infoFireAlert("error", "Terjadi kesalahan pada server.");
                $("#btn_submit").prop("disabled", false).text("Simpan");
            });
    });

    $("#l_schPemeriksaan").on("click", ".a_view", function(e) {
        e.preventDefault();
        var datas = listSchPemeriksaan.row($(this).parents('tr')).data();
        var id = datas.lampiran;

        window.open(APP_URL + "/storage/file/mesin_tahunan/" + id, '_blank');
    });


    $('#tb_aprHasilPekerjaan').on('click', '.dtlPekerjaan', function() {
        var datas = listAprHasilPekerjaan.row($(this).parents('tr')).data();

        $("#dtlId").val(datas.id_report_pekerjaan);
        $("#dtlStatusPekerjaan").val(datas.status_pekerjaan);
        $("#lpDesk").val(datas.deskripsi);
        $("#lpDesk_2").val(datas.deskripsi_2);

        var tanggalData = datas.tgl_pekerjaan;

        // Membuat objek Date dari tanggalData
        var tanggal = new Date(tanggalData);
        var tanggalFormatted = ('0' + tanggal.getDate()).slice(-
            2);
        var bulanFormatted = ('0' + (tanggal.getMonth() + 1)).slice(-
            2);
        var tahunFormatted = tanggal.getFullYear();
        var tanggalFinal = tanggalFormatted + '-' + bulanFormatted + '-' + tahunFormatted;

        $("#tglPekerjaan").html(tanggalFinal);

        $('#tb_hasilPerbaikan').DataTable().destroy();
        var listHasilPerbaikan = $('#tb_hasilPerbaikan').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'mtc/list_hasil_perbaikan',
                type: "GET",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.tgl = datas.tgl_pekerjaan;
                    d.shift = datas.shift;
                }
            },

            columns: [{
                    data: 'nama_mesin',
                    name: 'nama_mesin'
                },
                {
                    data: 'no_urut_mesin',
                    name: 'no_urut_mesin',
                },
                {
                    data: 'masalah',
                    name: 'masalah'
                },
                {
                    data: 'tindakan',
                    name: 'tindakan'
                },
                {
                    data: 'total_jam_perbaikan',
                    name: 'total_jam_perbaikan',
                    render: $.fn.dataTable.render.number(',', '.', 2)
                },
            ]
        });

        $('#modal_rp').modal('show');
    });

    $("#btn_lp").on("click", function() {
        $("#collapseLP").collapse("toggle");
        $("#lpShift").val(null).trigger('change');
    });

    $("#frm_ap").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();

        $.ajax({
                type: "PATCH",
                url: APP_BACKEND + "mtc/upd_hasil_pekerjaan",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: datas,
                dataType: "json",
            })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    $('#modal_rp').modal('toggle');
                    listAprHasilPekerjaan.ajax.reload();
                } else
                    infoFireAlert("success", resp.message);

            })
            .fail(function() {
                $("#error").html(
                    "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                );
            });
    });


});

var listSchPemeriksaan;
function getListSchPemeriksaan (){
    if ($.fn.DataTable.isDataTable('#l_schPemeriksaan')) {
        listSchPemeriksaan.ajax.reload();
    } else {
            listSchPemeriksaan = $('#l_schPemeriksaan').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'mtc/list_sch_pemeriksaan',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.tgl_awal = $("#tgl_awal").val();
                    d.tgl_akhir = $("#tgl_akhir").val();
                    d.status = $("#status_sch").val();
                    d.jenis = $("#jenis").val();
                },
                complete: function() {
                    $("#btn_reload").attr("disabled", false);
                },
                error: function() {
                    $("#btn_reload").attr("disabled", false);
                },
            },
            columnDefs: [{
                    targets: [0],
                    visible: false,
                    searchable: false
                },
                {
                    targets: [7],
                    data: null,
                    // defaultContent: "<a href='#' class='a_lampiran'><i class='fas fa-paperclip'> Add</i></a>"
                    render: function(data, type, row, meta) {
                        if (data.tanggal_selesai == "" || data.tanggal_selesai == null) {
                            return "";
                        } else if (data.lampiran == "" || data.lampiran == null) {
                            return "<a href='#' class='a_lampiran'><i class='fas fa-paperclip'> Add</i></a>";
                        } else {
                            return "<a href='#' class='a_view'><b style='color:green'> View</b></a>";
                        }
                    }


                }
            ],

            columns: [{
                    data: 'id_sch_mesin_tahunan',
                    name: 'id_sch_mesin_tahunan'
                },
                {
                    data: 'jadwal_perbaikan',
                    name: 'jadwal_perbaikan'
                },
                {
                    data: 'no_induk_mesin',
                    name: 'no_induk_mesin'
                },
                {
                    data: 'nama_mesin',
                    name: 'nama_mesin'
                },
                {
                    data: 'no_urut_mesin',
                    name: 'no_urut_mesin',
                    className: "text-center"
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'tanggal_selesai',
                    name: 'tanggal_selesai'
                },

            ]
        });
    }
}

var listAprHasilPekerjaan;
function getAprHasilPekerjaan (){
    if ($.fn.DataTable.isDataTable('#tb_aprHasilPekerjaan')) {
        listAprHasilPekerjaan.ajax.reload();
    } else {
        listAprHasilPekerjaan = $("#tb_aprHasilPekerjaan").DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'mtc/apr_hasil_pekerjaan',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.tgl_awal = $("#apDate1").val();
                    d.tgl_akhir = $("#apDate2").val();
                    d.shift = $("#apShift").val();
                }
            },
            columnDefs: [{
                targets: [0],
                visible: false,
                searchable: false
            }, {
                targets: [7],
                data: null,
                render: function(data, type, row, meta) {
                    return "<a href = '#' style='font-size:16px' class = 'dtlPekerjaan '><i class='far fa-clipboard'>  Detail </i></a>";
                }
            }],

            columns: [{
                    data: 'id_report_pekerjaan',
                    name: 'id_report_pekerjaan'
                },
                {
                    data: 'tgl_pekerjaan',
                    name: 'tgl_pekerjaan'
                },
                {
                    data: 'shift',
                    name: 'shift'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'status_pekerjaan',
                    name: 'status_pekerjaan'
                },
                {
                    data: 'approve',
                    name: 'approve'
                },
                {
                    data: 'tgl_approve',
                    name: 'tgl_approve'
                },

            ],
            fnRowCallback: function(nRow, data, iDisplayIndex, iDisplayIndexFull) {
                if (data.status_pekerjaan == "Open") {
                    $('td', nRow).css('color', 'Red');
                }
            },
        });
    }
}


function formReset (){
    $("#frm_sch").trigger('reset');
    $("#nomer_induk_mesin").val(null).trigger('change');
    $("#s_schedule").val(null).trigger('change');
}

