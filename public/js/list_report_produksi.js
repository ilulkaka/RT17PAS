    $(document).ready(function() {

        $('#tb_detail_hasil_produksi').on('click', '.btn-hapus', function() {
            var data = list_hasil_produksi.row($(this).parents('tr')).data();
            var conf = confirm("Apakah Lot No. " + data.lot_no + " akan dihapus?");
            if (conf) {
                $.ajax({
                        type: "POST",
                        url: APP_URL + "/api/hapus/lotno_hasilproduksi",
                        headers: {
                            "token_req": key
                        },
                        data: {
                            "id": data.id_hasil_produksi
                        },
                        dataType: "json",
                    })
                    .done(function(resp) {
                        if (resp.success) {
                            alert("Hapus Lot No berhasil");
                            location.reload();
                        } else {
                            alert(resp.message);
                        }
                        $("#error").html(
                            "<div class='alert alert-danger'><div>Error</div></div>");
                    })
                    .fail(function() {
                        $("#error").html(
                            "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                        );

                    });
            }
        });

        $("#btn_reload").click(function() {
            get_hasilProduksi();
        });

        var detail_ng = $('#tb_ng').DataTable({

        });

        $('#tb_detail_hasil_produksi').on('click', '.detailng', function(e) {
            e.preventDefault();
            var data = list_hasil_produksi.row($(this).parents('tr')).data();
            get_details_ng(data);
            $("#detaillist").val(data.id_hasil_produksi);
            $('#modal-NG').modal('show');
        });

        $("#btn-close-list").click(function() {
            $('#modal-NG').modal('hide');
        });


        $("#btn-excel").click(function() {
            var tgl_awal = $('#tgl_awal').val();
            var tgl_akhir = $("#tgl_akhir").val();
            var selectline = $("#selectline").val();

            var c = confirm("Download Hasil Produksi periode " + tgl_awal + "  Sampai  " + tgl_akhir);
            if (c) {
                spinner_on();
                $.ajax({
                    url: APP_BACKEND + 'produksi/get_excel_hasilproduksi',
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: 'json',
                    data: {
                        "tgl_awal": tgl_awal,
                        "tgl_akhir": tgl_akhir,
                        "selectline": selectline
                    },
                    success: function(response) {
                        spinner_off();
                        if(response.success){
                            var fpath = response.file;
                            window.open(fpath, '_blank');
                        } else {
                            alert (response.message);
                        }
                    }
                });
            }
        });


        $('#pilih_item').click(function() {
            var data = list_ng.row({
                selected: true
            }).data();
            if (!data) {
                alert('Item Belum dipilih !');
            } else {
                var c = confirm("Hapus Data NG  " + data.kode_ng + "  " + data.type_ng + "?");
                if (c) {
                    $.ajax({
                            type: "POST",
                            url: APP_URL + "/api/produksi/deleteng",
                            headers: {
                                "token_req": key
                            },
                            data: data,
                            dataType: "JSON",
                        })
                        .done(function(resp) {
                            if (resp.success) {
                                alert(resp.message);
                                location.reload();
                            } else {
                                alert(resp.message)
                            }
                        })
                        .fail(function() {
                            $("#error").html(
                                "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                            );
                        });
                }
            }
        });
            
        });
     

    var list_hasil_produksi;

    function get_hasilProduksi (){
        if ($.fn.DataTable.isDataTable('#tb_detail_hasil_produksi')) {
            list_hasil_produksi.ajax.reload();
        } else {
            list_hasil_produksi = $('#tb_detail_hasil_produksi').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                // responsive: true,
                // language: {
                //     lengthMenu: "Tampilkan _MENU_  data per halaman", // Ganti teks default
                // },
                
                ajax: {
                    url: APP_BACKEND + 'produksi/inquery_report_detail',
                    type: "GET",
                    beforeSend: function(xhr) {
                        $("#btn_reload").attr("disabled", true);
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: 'json',
                    data: function(d) {
                        d.tgl_awal = $("#tgl_awal").val();
                        d.tgl_akhir = $("#tgl_akhir").val();
                        d.selectline = $("#selectline").val();
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
                        targets: [16],
                        data: null,
                        defaultContent: "<button class='btn btn-hapus btn-sm'><i class='far fa-trash-alt'></i></button>"
                    }
                ],
    
                columns: [{
                        data: 'id_hasil_produksi',
                        name: 'id_hasil_produksi'
                    },
                    {
                        data: 'line1',
                        name: 'line1'
                    },
                    {
                        data: 'tgl_proses',
                        name: 'tgl_proses'
                    },
                    {
                        data: 'part_no',
                        name: 'part_no'
                    },
                    {
                        data: 'lot_no',
                        name: 'lot_no'
                    },
                    {
                        data: 'shape',
                        name: 'shape'
                    },
                    {
                        data: 'ukuran_haba',
                        name: 'ukuran_haba'
                    },
                    {
                        data: 'incoming_qty',
                        name: 'incoming_qty'
                    },
                    {
                        data: 'finish_qty',
                        name: 'finish_qty'
                    },
                    {
                        data: 'ng_qty',
                        name: 'ng_qty',
                        render: function(data, type, row, meta) {
                            if (data > 0) {
                                return "<u><a href='' class='detailng'>" + data + "</a></u>";
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'pro',
                        name: 'pro',
                        render: $.fn.dataTable.render.number(',', '.', 2, '', ' %')
                    },
                    {
                        data: 'operator',
                        name: 'operator'
                    },
                    {
                        data: 'shift',
                        name: 'shift'
                    },
                    {
                        data: 'no_mesin',
                        name: 'no_mesin'
                    },
                    {
                        data: 'total_cycle',
                        name: 'total_cycle',
                        render: $.fn.dataTable.render.number(',', '.', 0, '')
                    },
                    {
                        data: 'remark',
                        name: 'remark'
                    },
                ]
            });
        }
    }

    function get_details_ng(data) {
        var detail_ng1 = $('#t_detail_NG').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'produksi/detail_ng',
                type: "GET",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: {
                    "id_hasil_produksi": data
                },
            },
            columns: [

                {
                    data: 'ng_code',
                    name: 'ng_code'
                },
                {
                    data: 'ng_type',
                    name: 'ng_type'
                },
                {
                    data: 'ng_qty',
                    name: 'ng_qty'
                },

            ]


        });
    }