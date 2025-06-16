$(document).ready(function() {



    let tahun = $('#periode').val();
    loadTable(tahun); // Panggil loadTable dengan tahun yang sudah dipilih
    

    $('#btn_reload').on('click', function () {
        let tahun = $('#periode').val();
        loadTable(tahun);
    });

    

    document.getElementById('blok').addEventListener('input', function (e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (value.length > 2) {
            value = value.slice(0, 3) + '/' + value.slice(3);
        }
        e.target.value = value;
    });

    $("#periode_awal").on("change", function() {
        $("#periode_akhir").val($(this).val());
    });

    $("#add_iuran").on("click", function() {
        $("#modal_add_iuran").modal("show");
    });

    $("#frmAddIuran").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "POST",
            url: APP_BACKEND + "keuangan/ins_iuran_warga",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    $("#frmAddIuran")[0].reset();
                    table.ajax.reload(null, false);
                    $("#btn_submit").prop("disabled", false).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                    $("#btn_submit").prop("disabled", false).text("Simpan");
                }
            })
    });

    $('#tb_list_iuran').off('click', '.btn-detail').on('click', '.btn-detail', function () {
        const blok = $(this).data('blok');
        const periode = $('#periode').val(); 

        $("#diw_blok").text(blok);
        $("#diw_periode").text(periode);

        getDetailIuranWarga();
        $("#modal_detail_iuran_warga").modal("show");
    });

    $('#tb_detail_iuran_warga').on('click', '.btn-hapus', function () {
        var row = $(this).closest('tr');
        var datas = detailIuranWarga.row(row).data();

        Swal.fire({
            title: 'Hapus Data',
            text: "Apakah Anda yakin ingin menghapus data ini?",
            type: 'question',
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",            
            cancelButtonText: "Cancel",
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "delete",
                    url: APP_BACKEND + "keuangan/del_iuran_warga/" + datas.id_iuran,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: "json",
                })
                .done(function(resp) {
                    if (resp.success) {
                        fireAlert('success', resp.message);
                        detailIuranWarga.ajax.reload(null, false);
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


var detailIuranWarga;
function getDetailIuranWarga (){
    if ($.fn.DataTable.isDataTable('#tb_detail_iuran_warga')) {
        detailIuranWarga.ajax.reload();
    } else {
        detailIuranWarga = $('#tb_detail_iuran_warga').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'keuangan/detail_iuran_warga',
                type: "GET",
                beforeSend: function(xhr) {
                    // $("#btn_reload").attr("disabled", true);
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.periode = $("#diw_periode").text();
                    d.blok = $("#diw_blok").text();
                },
                // complete: function() {
                //     $("#btn_reload").attr("disabled", false);
                // },
                // error: function() {
                //     $("#btn_reload").attr("disabled", false);
                // }
            },
            columnDefs: [{
                    targets: [0],
                    visible: false,
                    searchable: false
                },
                {
                    targets: [5],
                    data: null,
                    defaultContent: "<a href='#' class='btn btn-sm btn-hapus'>Hapus</a>"
                },

            ],

            columns: [{
                    data: 'id_iuran',
                    name: 'id_iuran',
                },
                {
                    data: 'tgl_bayar',
                    name: 'tgl_bayar',
                    className: 'text-left',
                    render: function (data, type, row) {
                        if (!data) return '-';

                        let dateObj = new Date(data);
                        let day = String(dateObj.getDate()).padStart(2, '0');
                        let month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        let year = dateObj.getFullYear();

                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'nominal',
                    name: 'nominal',
                },
                {
                    data: 'periode',
                    name: 'periode',
                    className: 'text-left',
                    render: function (data, type, row) {
                        if (!data) return '-';

                        let dateObj = new Date(data);
                        let day = String(dateObj.getDate()).padStart(2, '0');
                        let month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        let year = dateObj.getFullYear();

                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'inputor',
                    name: 'inputor',
                },
            ],
        });
    }
}

var table; // Deklarasikan variabel `table` di sini

// Fungsi untuk membuat kolom dinamis berdasarkan tahun
function generateColumns(tahun) {
    let columns = [
        { data: 'blok', title: 'Blok' }
    ];

    for (let i = 1; i <= 12; i++) {
        let bulan = i.toString().padStart(2, '0') + '-' + tahun;
        columns.push({
            data: bulan,
            title: bulan,
            render: function (data) {
                return data ? data : '-';
            }
        });
    }

      // Tambahkan kolom aksi (tombol klik)
    columns.push({
        data: null,
        title: 'Aksi',
        className: 'text-center',
        width: '50px',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            return `<button class="btn btn-sm btn-info btn-detail rounded-0" data-blok="${row.blok}">Detail</button>`;
            // return `
            //     <button class="btn btn-sm btn-info" onclick="detail('${row.blok}')">Detail</button>
            //     <button class="btn btn-sm btn-warning" onclick="edit('${row.blok}')">Edit</button>
            //     <button class="btn btn-sm btn-danger" onclick="hapus('${row.blok}')">Hapus</button>
            // `;
        }
    });

    return columns;
}

function loadTable(tahun) {
    if (table) {
        table.destroy();
        $('#tb_list_iuran').empty(); // kosongkan thead & tbody
    }

    table = $('#tb_list_iuran').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
        ajax: {
            url: APP_BACKEND + 'keuangan/list_iuran_warga',
            beforeSend: function(xhr) {
                $("#btn_reload").attr("disabled", true);
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            type: 'GET', // atau POST jika di backend pakai POST
            data: function (d) {
                d.periode = tahun; // kirim tahun yang dipilih
            },
                complete: function() {
                    $("#btn_reload").attr("disabled", false);
                },
                error: function() {
                    $("#btn_reload").attr("disabled", false);
                }
        },
        columns: generateColumns(tahun),
        scrollX: true, // agar bisa scroll ke kanan
        ordering: false
    });
}



   