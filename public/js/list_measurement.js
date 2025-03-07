        $(document).ready(function() {

            // ======================== Header Button ===========================

            $("#btn_approve").on("click", function() {
                $("#collapseApprove").collapse("show");
                $("#collapseProduction").collapse("hide");
            });

            $("#btn_production").on("click", function() {
                $("#collapseApprove").collapse("hide");
                $("#collapseProduction").collapse("show");
            });

            // Event listener for all buttons
            $('.btn-outline').on('click', function() {
                // Remove 'btn-active' class from all buttons
                $('.btn-outline').removeClass('btn-active');

                // Add 'btn-active' class to the clicked button
                $(this).addClass('btn-active');
            });

            // ========================= Approve ============================
           getListApprove();

            $('#selectAllApprove').on("change", function() {
                var checked = this.checked;
                $('.row-select-checkbox').prop('checked', checked);
                listApprove.rows().select(checked);
            });

            $('#btn_updSelectedApprove').on('click', function() {
                var selectedRows = listApprove.rows({
                    selected: true
                }).data().toArray();

                // Ambil nilai NIK dari baris yang dipilih
                var selectedIDs = selectedRows.map(function(row) {
                    return row.id_meas_st;
                });

                // Periksa apakah array selectedNIKs kosong
                if (selectedIDs.length === 0) {
                    infoFireAlert("warning","Record tidak ada yang dipilih.");
                    return;
                }

                $.ajax({
                        url: APP_BACKEND + 'meas/upd_selected_approve',
                        type: 'PATCH',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + key);
                        },
                        data: {
                            'selectedIDs': selectedIDs,
                        },
                    })

                    .done(function(resp) {
                        if (resp.success) {
                            fireAlert('success',resp.message);
                            listApprove.ajax.reload(null, false);
                            listProduction.ajax.reload(null, false);
                        } else {
                            fireAlert('error',resp.message);
                        }
                    });
            });


            // ========================= Production ============================
            var listProduction = $('#tb_list_production').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,

                ajax: {
                    url: APP_BACKEND + 'meas/list_production',
                    type: "GET",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                },

                columnDefs: [{
                        targets: [0],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [1],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [12],
                        data: null,
                        render: function(data, type, row, meta) {
                            if (data.abnormalitas == 0) {
                                return "<button type='button' class='btn btn-block btn-outline-danger btn-sm btn-flat'>Msr Abnormal</button>";
                            } else {
                                return '<i style="color:green; font-size:15px">Proses pelaporan QA...</i>';
                            }
                        }
                    },
                ],

                columns: [{
                        data: 'id_meas',
                        name: 'id_meas'
                    },
                    {
                        data: 'id_meas_st',
                        name: 'id_meas_st'
                    },
                    {
                        data: 'no_registrasi',
                        name: 'no_registrasi'
                    },
                    {
                        data: 'nama_alat_ukur',
                        name: 'nama_alat_ukur'
                    },
                    {
                        data: 'ukuran',
                        name: 'ukuran'
                    },
                    {
                        data: 'jenis',
                        name: 'jenis'
                    },
                    {
                        data: 'warna_identifikasi',
                        name: 'warna_identifikasi'
                    },
                    {
                        data: 'tgl_penyerahan',
                        name: 'tgl_penyerahan',
                        className: 'text-left'
                    },
                    {
                        data: 'range_cek',
                        name: 'range_cek',
                        className: 'text-center'
                    },
                    {
                        data: 'tgl_kalibrasi',
                        name: 'tgl_kalibrasi'
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi'
                    },
                    {
                        data: 'rak_no',
                        name: 'rak_no'
                    },
                ],
                fnRowCallback: function(nRow, data, iDisplayIndex, iDisplayIndexFull) {
                    if (data.status_meas == 'NG') {
                        $('td', nRow).css('color', 'red');
                    }
                },
            });

            $('#selectAllProduction').on("change", function() {
                var checked = this.checked;
                $('.row-select-checkbox').prop('checked', checked);
                listProduction.rows().select(checked);
            });

            $('#tb_list_production').on('click', '.btn-outline-danger', function(e) {
                e.preventDefault();
                var datas = listProduction.row($(this).parents('tr')).data();

                $("#frm_ra").trigger('reset');
                $("#ra_idMeas").val(datas.id_meas);
                $("#ra_idMeasST").val(datas.id_meas_st);
                $("#ra_tglPenyerahan").val(datas.tgl_penyerahan);

                $("#ra_noRegistrasi").val(datas.no_registrasi);
                $("#ra_namaAlat").val(datas.nama_alat_ukur);
                $("#ra_ukuran").val(datas.ukuran);
                $("#ra_jenis").val(datas.jenis);
                $("#ra_kode").val(datas.kode);
                $("#ra_section").val(datas.section);

                $('#modal_ra').modal('show');
            });

            $("#frm_ra").on("submit", function(e) {
                e.preventDefault();
                var data = $(this).serialize();
                //alert(data);
                $.ajax({
                        type: "PATCH",
                        url: APP_BACKEND + "meas/upd_report_abnormal",
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + key);
                        },
                        data: data,
                    })
                    .done(function(resp) {
                        if (resp.success) {
                            fireAlert("success", resp.message);
                            $('#modal_ra').modal('toggle');
                            listProduction.ajax.reload(null, false);
                        } else {
                            fireAlert("error", resp.message);
                        }
                    })
            });

        });

        var listApprove;
        function getListApprove (){
            if ($.fn.DataTable.isDataTable('#tb_list_approve')) {
                listApprove.ajax.reload();
            } else {
                listApprove = $('#tb_list_approve').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ordering: false,
    
                    ajax: {
                        url: APP_BACKEND + 'meas/list_approve',
                        type: "GET",
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + key);
                        },
                    },
    
                    columnDefs: [{
                            targets: [0],
                            visible: false,
                            searchable: false,
                        },
                        {
                            targets: [1],
                            visible: false,
                            searchable: false
                        },
                        {
                            orderable: false,
                            className: 'row-select-checkbox',
                            targets: 2
                        },
                    ],
    
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    },
                    order: [
                        [1, 'asc']
                    ],
    
                    columns: [{
                            data: 'id_meas',
                            name: 'id_meas'
                        },
                        {
                            data: 'id_meas_st',
                            name: 'id_meas_st'
                        },
                        {
                            orderable: false,
                            className: 'select-checkbox',
                            data: null,
                            defaultContent: ''
                        },
                        {
                            data: 'no_registrasi',
                            name: 'no_registrasi'
                        },
                        {
                            data: 'nama_alat_ukur',
                            name: 'nama_alat_ukur'
                        },
                        {
                            data: 'ukuran',
                            name: 'ukuran'
                        },
                        {
                            data: 'jenis',
                            name: 'jenis'
                        },
                        {
                            data: 'warna_identifikasi',
                            name: 'warna_identifikasi'
                        },
                        {
                            data: 'tgl_penyerahan',
                            name: 'tgl_penyerahan',
                            className: 'text-left'
                        },
                        {
                            data: 'range_cek',
                            name: 'range_cek',
                            className: 'text-center'
                        },
                        {
                            data: 'lokasi',
                            name: 'lokasi'
                        },
                        {
                            data: 'rak_no',
                            name: 'rak_no'
                        },
                    ],
                    fnRowCallback: function(nRow, data, iDisplayIndex, iDisplayIndexFull) {
                        if (data.status_meas == 'NG') {
                            $('td', nRow).css('color', 'red');
                        }
                    },
                });
            }
        }
           