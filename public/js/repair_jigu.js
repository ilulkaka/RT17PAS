$(document).ready(function() {

    $('.select2').select2({
        theme: 'bootstrap-5'
    });


    get_namaMesin();
    get_notifReqJigu();

    $("#btn_reload").on("click", function() {
        get_list_repair_jigu();
    });

    $("#permintaan1").on("change", function(){
        var d = new Date();
                var tahun = d.getFullYear();
                var bulan = d.getMonth() + 1;
                var tanggal = d.getDate();
                var dept = $("#permintaan1").val();
                var permintaan = $("#permintaan1 option:selected").text();
                $("#permintaan").val(permintaan);
                $("#unik").val(permintaan);
                var tgl = tahun + "-" + bulan + "-" + tanggal;
                $.ajax({
                    type: "GET",
                    url: APP_BACKEND + 'tech/no_req_jigu',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    data: {
                        "permintaan": dept,
                        "tanggal_permintaan": tgl
                    },
                    dataType: "json",

                    success: function(response) {
                        var nomer = response[0].no_laporan;
                        $("#no_laporan").val(nomer);
                        $("#no_laporan_1").val(nomer);

                    }

                });
    });

    $(document).on('click', '.modal', function(event) {
        if ($(event.target).hasClass('modal')) {
            event.stopPropagation();
        }
    });

    $('#modal_drj').modal({
        backdrop: 'static',
        keyboard: false
    });

    $(document).on('click', '.lrj_detail', function() {
        var datas = list_repair_jigu.row($(this).parents('tr')).data();
        $("#drj_idPermintaan").val(datas.id_permintaan);
        $("#drj_noLaporan").val(datas.no_laporan);
        $("#drj_permintaan").val(datas.permintaan);
        $("#drj_jenisItem").val(datas.jenis_item);
        $("#drj_namaMesin").val(datas.nama_mesin);
        $("#drj_item").val(datas.nama_item);
        $("#drj_ukuran").val(datas.ukuran);
        $("#drj_qty").val(datas.qty);
        $("#drj_satuan").val(datas.satuan);
        $("#drj_nouki").val(datas.nouki);
        $("#drj_alasan").val(datas.alasan);
        $("#drj_permintaanPerbaikan").val(datas.permintaan_perbaikan);
        $("#drj_tindakanPerbaikan").val(datas.tindakan_perbaikan);
        $("#drj_oprTech").val(datas.operator_tch);
        $("#drj_tglSelesaiTech").val(datas.tanggal_selesai_tch);
        $("#drj_qtySelesai").val(datas.qty_selesai_tch);
        $("#drj_material").val(datas.material);
        $("#drj_status").val(datas.status);
        $("#drj_userTerima").val(datas.qty_selesai);

        var status_permintaan = datas.status;
        if(status_permintaan === 'Waiting User'){
            $("#btn_drj").prop('disabled',false);
            $("#drj_userTerima").prop('disabled',false);
        } else {
            $("#btn_drj").prop('disabled',true);
            $("#drj_userTerima").prop('disabled',true);
        }
        
        $("#modal_drj").modal('show');
    });

    $("#frm_drj").on("submit", function (e){
        e.preventDefault();
        // fireAlert('error','test');
        var datas = $(this).serialize();
        $.ajax({
                url: APP_BACKEND + 'tech/terima_jigu',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                type: 'PATCH',
                dataType: 'json',
                data: datas,
            })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert('success',resp.message);

                    $("#btn_drj").prop('disabled',true);
                    $("#drj_userTerima").prop('disabled',true);

                    list_repair_jigu.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred: Update gagal .',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + errorThrown,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            });
    })

});

var list_repair_jigu;
function get_list_repair_jigu (){
    if ($.fn.DataTable.isDataTable('#tb_repair_jigu')) {
        list_repair_jigu.ajax.reload();
    } else {
        list_repair_jigu = $('#tb_repair_jigu').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: false,

        ajax: {
            url: APP_BACKEND + 'tech/list_repair_jigu',
            type: "GET",
            beforeSend: function(xhr) {
                $("#btn_reload").attr("disabled", true);
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            dataType: 'json',
            data: function(d) {
                d.tgl_awal = $("#tgl_awal").val();
                d.tgl_akhir = $("#tgl_akhir").val();
                d.status_permintaan = $("#status_permintaan").val() || 'All';
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
                { 
                    targets: [0], 
                    visible: false, 
                    searchable: false 
                },
                {
                    targets: [13],
                    data: null,
                   defaultContent: "<button type='button' class='btn btn-info btn-sm lrj_detail' ><u> Detail </u></button>"
                }
            ],
            columns: [
                { data: 'id_permintaan', name: 'id_permintaan' },
                { data: 'tanggal_permintaan', name: 'tanggal_permintaan', className:'text-left' },
                { data: 'no_laporan', name: 'no_laporan' },
                { data: 'permintaan', name: 'permintaan' },
                { data: 'jenis_item', name: 'jenis_item' },
                { data: 'nama_mesin', name: 'nama_mesin' },
                { data: 'nama_item', name: 'nama_item' },
                { data: 'ukuran', name: 'ukuran' },
                { data: 'qty', name: 'qty' },
                { data: 'satuan', name: 'satuan' },
                { data: 'permintaan_perbaikan', name: 'permintaan_perbaikan' },
                { data: 'nouki', name: 'nouki', className:'text-left' },
                { data: 'status', name: 'status' },
            ],
            fnRowCallback: function(nRow, data, iDisplayIndex, iDisplayIndexFull) {
                if (data.status == 'Tolak') {
                    $('td', nRow).css('color', 'red');
                } else if (data.status == 'Close') {
                    $('td', nRow).css('color', 'blue');
                } else if (data.status == 'Proses') {
                    $('td', nRow).css('color', 'green');
                }
            }
        });
    }
}

function get_namaMesin (){
    $.ajax({
        url: APP_BACKEND + 'tech/get_namaMesin',
        type: 'GET',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: 'json',
    })
    .done(function(resp) {
        if (resp.success) {
            const selectNamaMesin = $('#nama_mesin');
            selectNamaMesin.empty();
            selectNamaMesin.append('<option value="">Pilih Nama Mesin...</option>');

            resp.datas.forEach(function (namaMesin) {
                selectNamaMesin.append('<option value="' + namaMesin.nama_mesin + '">' + namaMesin.nama_mesin + '</option>');
            });

            selectNamaMesin.trigger('change');

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


function get_notifReqJigu ()
{
    $.ajax({
        url: APP_BACKEND + 'tech/notif_req_jigu',
        type: 'GET',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: 'json',
    })
    .done(function(resp) {
        if (resp.success) {
            if( resp.notif_reqJigu >= 1){
                $("#notif_reqJigu").html("Request Selesai "+ resp.notif_reqJigu);
                $("#btn_simpan").prop('disabled',true).removeClass("btn-primary")
                .addClass("btn-danger").text("Jigu request has been completed, Please Confirm .");
            } else {
                $("#notif_reqJigu").html('');
                $("#btn_simpan").prop('disabled',false).text("Simpan");
            }
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