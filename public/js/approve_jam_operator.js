$(document).ready(function() {

        $("#tb_approve_jam_operator").on('click', '.btn-edit', function() {
        var data = list_approve_jam_operator.row($(this).parents('tr')).data();
        $("#id_jam_kerja").val(data.id_jam_kerja);
        $("#e-tgljamkerja").html(data.tgl_jam_kerja);
        $("#e-operator").html(data.operator);
        $("#e-shift").html(data.shift);
        $("#e-jamtotal").val(data.jam_total);
        $("#e-keterangan").val(data.ket);
        $("#modal-editjamopr").modal("show");
    });

    $("#form-jamkerjaoperator").submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
                url: APP_BACKEND + 'produksi/edit_jamkerjaoperator',
                type: "PATCH",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                dataType: 'json',
                data: data,
            })
            .done(function(resp) {
                if (resp.success) {
                    alert(resp.message);
                    // location.reload();
                    $("#modal-editjamopr").modal('toggle');
                    list_approve_jam_operator.ajax.reload(null, false);
                } else
                    alert(resp.message);
            })
            .fail(function() {
                $("#error").html(
                    "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                );

            })
            .always(function() {});
    });

    $("#btn_reload").click(function() {
        get_listJamOperator();
    });

    $('#tb_approve_jam_operator').on('click', '.btn-update', function() {
        var data = list_approve_jam_operator.row($(this).parents('tr')).data();

        var conf = confirm(data.operator + "   " + data.shift + "   " + data.jam_total + " Jam" +
            "\n" + "Keterangan  : " + data.ket);
        if (conf) {
                $.ajax({
                    url: APP_BACKEND + 'produksi/approve_jam_kerja',
                    type: "PATCH",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: 'json',
                    data: {
                        "id_jam_kerja": data.id_jam_kerja
                    },
                })
                .done(function(resp) {
                    if (resp.success) {
                        alert(resp.message);
                        list_approve_jam_operator.ajax.reload(null, false);
                    } else {
                        alert(resp.message);
                    }
                })
                .fail(function() {
                    $("#error").html(
                        "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                    );

                });
        }
    });

});

var list_approve_jam_operator;

function get_listJamOperator() {
    if ($.fn.DataTable.isDataTable('#tb_approve_jam_operator')) {
        list_approve_jam_operator.ajax.reload();
    } else {
        list_approve_jam_operator = $('#tb_approve_jam_operator').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'produksi/l_approve_jam_operator',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                dataType: 'json',
                data: function(d) {
                    d.tgl_awal = $("#tgl_awal").val();
                    d.tgl_akhir = $("#tgl_akhir").val();
                    d.selectshift = $("#selectshift").val() || 'All';
                    d.selectline = $("#selectline").val() || 'All';
                    d.selectstatus = $("#selectstatus").val() || 'Pending';
                },
                complete: function() {
                    // Tombol reload aktif setelah data berhasil dimuat
                    $("#btn_reload").attr("disabled", false);
                },
                error: function() {
                    $("#btn_reload").attr("disabled", false);
                }
            },
            columnDefs: [
                { targets: [0], visible: false, searchable: false },
                {
                    targets: [10],
                    data: null,
                    defaultContent: "<button class='btn btn-edit btn-sm btn-flat'>Edit</button> <button class='btn btn-update btn-sm btn-flat'>Approve</button>"
                },
            ],
            columns: [
                { data: 'id_jam_kerja', name: 'id_jam_kerja' },
                { data: 'tgl_jam_kerja', name: 'tgl_jam_kerja', className:'text-left' },
                { data: 'operator', name: 'operator' },
                { data: 'nama_line', name: 'nama_line' },
                { data: 'shift', name: 'shift' },
                { data: 'jam_total', name: 'jam_total' },
                { data: 'ket', name: 'ket' },
                { data: 'status', name: 'status' },
                { data: 'approve', name: 'approve' },
                { data: 'tgl_approve', name: 'tgl_approve' },
            ]
        });
    }
}
