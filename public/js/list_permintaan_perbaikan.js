$(document).ready(function() {

    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    getNotifPermintaanPerbaikan();

    $("#btn_reload").on("click", function() {
        getListPermintaanPerbaikan();
    });

    document.querySelectorAll('input[name="kategori"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === "r_mesin") {
                $('#nomer_induk_mesin').prop('disabled', false);
                $("#nomer_induk_mesin").val(null).trigger('change');

                $("#nama_mesin").prop('readonly',true);
                $("#nama_mesin").val('');
            } else {
                $('#nomer_induk_mesin').prop('disabled', true);
                $("#nomer_induk_mesin").val(null).trigger('change');

                $("#nama_mesin").prop('readonly',false);
                $("#nama_mesin").val('');
            }
        });
    });

    $("#shift").on("change",function(){
        $.ajax({
            type: "GET",
            url: APP_BACKEND + "mtc/nomer_permintaan_perbaikan",
            beforeSend: function(xhr) {
                $("#btn_reload").attr("disabled", true);
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            dataType: "json",

            success: function(response) {
                var nomer = response.no_perbaikan;
                $("#no_permintaan").val(nomer);
            }

        });
    });

    $("#frm_permintaan_perbaikan_mesin").on("submit", function(e){
        e.preventDefault();

        // var datas = $("#form_mesin").serialize();
        var datas = $(this).serialize();
        let pilihKategori = $('input[name="kategori"]:checked').val();
        let pilihLaporPPIC = $('input[name="ppic"]:checked').val();

        if (pilihKategori === 'r_mesin'){
            var conditions = [
                '1. Periksa kondisi Mesin (Lakukan pemeriksaan dengan mengoptimalkan 5 panca indera ) ?',
                '2. Periksa Apakah daya / sumberlistrik terpasang dengan baik dan benar ?',
                '3. Periksa kondisi Mesin secara Fungsi auto / manual ?',
                '4. Periksa Apakah Sensor atau Limit switch mesin sudah benar posisinya ?',
                '5. Periksa Apakah Tekanan Air, Angin dan Hidrolik sesuai dengan standartnya ?',
                '6. Periksa ke kondisi original (posisi awal) ?'
            ];
    
            function askCondition(index = 0) {
                if (index >= conditions.length) {

                    if(pilihLaporPPIC === 'Y'){
                        var conf = confirm("Permintaan ini akan disampaikan juga ke PPIC?");
                        if (conf) {
                            $.ajax({
                                type: "POST",
                                url: APP_BACKEND + "mtc/ins_permintaan_perbaikan_mesin",
                                beforeSend: function(xhr) {
                                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                                },
                                data: datas,
                            })
                            .done(function(resp) {
                                if (resp.success) {
                                    fireAlert("success", resp.message);
                                    location.reload();
                                } else {
                                    fireAlert("error", resp.message);
                                }
                            })
                        } 
                    } else {
                        $.ajax({
                            type: "POST",
                            url: APP_BACKEND + "mtc/ins_permintaan_perbaikan_mesin",
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader("Authorization", "Bearer " + key);
                            },
                            data: datas,
                        })
                            .done(function(resp) {
                                if (resp.success) {
                                    fireAlert("success", resp.message);
                                    location.reload();
                                } else {
                                    fireAlert("error", resp.message);
                                }
                            })
                    }
                    return; // Pastikan eksekusi selesai setelah update
                }
    
                Swal.fire({
                    title: 'Point Check',
                    text: conditions[index],
                    type: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.value) {
                        askCondition(index + 1);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: 'Cek Diperlukan',
                            text: 'Silakan periksa kembali kondisi Mesin.',
                            type: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
            askCondition();
        } else {
            if(pilihLaporPPIC === 'Y'){
                var conf = confirm("Permintaan ini akan disampaikan juga ke PPIC?");
                        if (conf) {
                            $.ajax({
                                type: "POST",
                                url: APP_BACKEND + "mtc/ins_permintaan_perbaikan_mesin",
                                beforeSend: function(xhr) {
                                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                                },
                                data: datas,
                            })
                            .done(function(resp) {
                                if (resp.success) {
                                    fireAlert("success", resp.message);
                                    location.reload();
                                } else {
                                    fireAlert("error", resp.message);
                                }
                            })
                        } 
            } else {
                $.ajax({
                    type: "POST",
                    url: APP_BACKEND + "mtc/ins_permintaan_perbaikan_mesin",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    data: datas,
                })
                .done(function(resp) {
                    if (resp.success) {
                        fireAlert("success", resp.message);
                        location.reload();
                    } else {
                        fireAlert("error", resp.message);
                    }
                })
            }
        }
    });

    $("#tb_permintaan_perbaikan").on('click', '.pending', function(e) {
        e.preventDefault();
        var datas = listPermintaanPerbaikan.row($(this).parents('tr')).data();

        if (datas.status == 'TempReject') {
            $("#idPerbaikan").val(datas.id_perbaikan);
            $("#keteranganReject").val(datas.ket_reject);
            $("#modalInfoReject").modal('show');
        } else {
            var selesai = moment(datas.tanggal_rencana_selesai).format('DD-MM-YYYY');
            $("#schd").html(selesai);
            $("#desk").html(datas.keterangan);
            $("#setatus").html(datas.status);

            $("#modalinfo").modal('toggle');
        }
    });

    $("#frm_reject").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();

        $.ajax({
                url: APP_BACKEND + 'mtc/apr_permintaan_reject',
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
                    location.reload();
                } else {
                    fireAlert('error', resp.message);
                }
            });
    });

    $("#tb_permintaan_perbaikan").on('click', '.btn-edit', function() {
        var datas = listPermintaanPerbaikan.row($(this).parents('tr')).data();
        $("#no-perbaikan").html(datas.no_perbaikan);
        $("#id-req").val(datas.id_perbaikan);
        $("#dept").html(datas.departemen);
        $("#edit-shift").html(datas.shift);
        $("#edit_no_mesin").html(datas.no_induk_mesin);
        // $('#edit-shift option[value=' + data.shift + ']').attr('selected', 'selected');
        // $("#select2-edit_no_mesin-container").html(data.no_induk_mesin);
        $("#edit_nama_mesin").html(datas.nama_mesin);
        $("#edit-masalah").val(datas.masalah);
        $("#edit-kondisi").val(datas.kondisi);
        $("#edit_no_induk").val(datas.no_induk_mesin);
        $("#modal_epp").modal("show");
    });

    $("#btn-update").on("click", function() {
        var shift = $("#edit-shift").val();
        var no_mesin = $("#edit_no_induk").val();
        var masalah = $("#edit-masalah").val();
        var kondisi = $("#edit-kondisi").val();
        var id_req = $("#id-req").val();
        $.ajax({
                type: "PATCH",
                url: APP_BACKEND + "mtc/edt_permintaan_perbaikan",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: {
                    "id": id_req,
                    "shift": shift,
                    "no_mesin": no_mesin,
                    "masalah": masalah,
                    "kondisi": kondisi
                },
                dataType: "json",
            })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert('success', resp.message);
                    $("#modal_epp").modal('toggle');
                    listPermintaanPerbaikan.ajax.reload(null, false);
                } else {
                    fireAlert('error', resp.message);
                }
            })
            .fail(function() {
                $("#error").html(
                    "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                );

            });
    });

    $("#tb_permintaan_perbaikan").on('click', '.btn-hapus', function() {
        var datas = listPermintaanPerbaikan.row($(this).parents('tr')).data();
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah request No. " + datas.no_perbaikan + " akan dihapus?",
            type: 'question',
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "delete",
                    url: APP_BACKEND + "mtc/del_permintaan_perbaikan/" + datas.id_perbaikan,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: "json",
                })
                .done(function(resp) {
                    if (resp.success) {
                        fireAlert('success', resp.message);
                        listPermintaanPerbaikan.ajax.reload(null, false);
                    } else {
                        fireAlert('error', resp.message);
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

    $("#tb_permintaan_perbaikan").on('click', '.btn-update', function() {
        var datas = listPermintaanPerbaikan.row($(this).parents('tr')).data();
        var id = datas.id_perbaikan;
        $("#conf_id").val(id);
        $("#btn_save").prop("disabled", true);
        $("#checkboxPrimary1").prop("checked", false);
        $("#modal_conf").modal('show');
    });

    $("#checkboxPrimary1").on("click", function() {
        if (this.checked) {
            $("#btn_save").prop("disabled", false);
        } else {
            $("#btn_save").prop("disabled", true);
        }
    });

    $("#btn_save").on("click", function() {
        var id_perbaikan = $("#conf_id").val();
        if ($("#checkboxPrimary1").prop("checked")) {
            $.ajax({
                type: "PATCH",
                url: APP_BACKEND + "mtc/upd_terima_perbaikan/"+id_perbaikan,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: {
                    "id": id_perbaikan,
                },
                dataType: "json",
            })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert('success', resp.message);
                    location.reload();
                    // $("#modal_conf").modal('toggle');
                    // listPermintaanPerbaikan.ajax.reload(null, false);
                } else {
                    fireAlert('error', resp.message);
                }
            })
            .fail(function() {
                $("#error").html(
                    "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                );

            });
        } else {
            alert("Konfirmasi pemeriksaan hasil perbaikan !")
        }
    });

    
    
});

var listPermintaanPerbaikan;
function getListPermintaanPerbaikan (){
    if ($.fn.DataTable.isDataTable('#tb_permintaan_perbaikan')) {
        listPermintaanPerbaikan.ajax.reload();
    } else {
        listPermintaanPerbaikan = $('#tb_permintaan_perbaikan').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'mtc/list_permintaan_perbaikan',
                type: "GET",
                beforeSend: function(xhr) {
                    $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
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
            columnDefs: [{
                    targets: [0],
                    visible: false,
                    searchable: false
                },
                {
                    targets: [13],
                    render: function(data, type, row, meta) {
                        if (data == 'pending' || data == 'scheduled' || data == 'TempReject') {
                            return "<a href='#'' class='pending'>" + data + "</a>";
                        } else {
                            return data;
                        }
                    }
                },
                {
                    targets: [14],
                    data: null,
                    render: function(data, type, row, meta) {
                        if (data.status == 'open') {
                            return "<button class='btn btn-edit'><i class='fa fa-edit'></i></button><button class='btn btn-hapus'><i class='fa fa-trash'></i></button>";
                        } else if(data.status == 'selesai'){
                            return "<button class='btn btn-update'><i class='fas fa-hand-holding-heart'> Terima</i></button>";
                        } else {
                            return "";
                        }
                    }
                }
            ],
    
            columns: [{
                    data: 'id_perbaikan',
                    name: 'id_perbaikan'
                },
                {
                    data: 'tanggal_rusak',
                    name: 'tanggal_rusak'
                },
                {
                    data: 'operator',
                    name: 'operator'
                },
                {
                    data: 'departemen',
                    name: 'departemen'
                },
                {
                    data: 'shift',
                    name: 'shift'
                },
                {
                    data: 'no_perbaikan',
                    name: 'no_perbaikan'
                },
                {
                    data: 'nama_mesin',
                    name: 'nama_mesin'
                },
                {
                    data: 'no_urut_mesin',
                    name: 'no_urut_mesin'
                },
                {
                    data: 'no_induk_mesin',
                    name: 'no_induk_mesin'
                },
                {
                    data: 'masalah',
                    name: 'masalah'
                },
                {
                    data: 'kondisi',
                    name: 'kondisi'
                },
                {
                    data: 'tindakan',
                    name: 'tindakan'
                },
                {
                    data: 'tanggal_selesai',
                    name: 'tanggal_selesai'
                },
                {
                    data: 'status',
                    name: 'status'
                },
            ],
            fnRowCallback: function(nRow, data, iDisplayIndex, iDisplayIndexFull) {
                if (data.status == "reject") {
                    $('td', nRow).css('color', 'Red');
                }
            },
        });
    }
}

function getNotifPermintaanPerbaikan ()
{
    $.ajax({
        url: APP_BACKEND + 'mtc/notif_permintaan_perbaikan',
        type: 'GET',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: 'json',
    })
    .done(function(resp) {
        if (resp.success) {
            if( resp.notif_permintaan_perbaikan >= 1){
                $("#notif_permintaan_perbaikan").html("Perbaikan Selesai "+ resp.notif_permintaan_perbaikan);
                $("#btn_simpan").prop('disabled',true).removeClass("btn-primary")
                .addClass("btn-danger").text("The repair request has been completed, please accept it.");
            } else {
                $("#notif_permintaan_perbaikan").html('');
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

