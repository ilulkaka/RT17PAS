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
                    // listIuran.ajax.reload(null, false);
                    $("#btn_submit").prop("disabled", false).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });
});


// var listIuranWarga;
// function getListIuranWarga (){
//     if ($.fn.DataTable.isDataTable('#tb_list_iuran')) {
//         listIuranWarga.ajax.reload();
//     } else {
//         listIuranWarga = $('#tb_list_iuran').DataTable({
//             responsive: true,
//             processing: true,
//             serverSide: true,
//             searching: false,
//             ordering: false,
//             ajax: {
//                 url: APP_BACKEND + 'keuangan/list_iuran_warga',
//                 type: "GET",
//                 beforeSend: function(xhr) {
//                     $("#btn_reload").attr("disabled", true);
//                     xhr.setRequestHeader("Authorization", "Bearer " + key);
//                 },
//                 data: function(d) {
//                     d.periode = $("#periode").val();
//                 },
//                 complete: function() {
//                     $("#btn_reload").attr("disabled", false);
//                 },
//                 error: function() {
//                     $("#btn_reload").attr("disabled", false);
//                 }
//             },
//             columnDefs: [{
//                     targets: [0],
//                     visible: false,
//                     searchable: false
//                 },

//             ],

//             columns: [{
//                     data: 'id_iuran',
//                     name: 'id_iuran',
//                 },
//                 {
//                     data: 'blok',
//                     name: 'blok',
//                 },
//                 {
//                     data: 'periode',
//                     name: 'periode',
//                 },
//                 {
//                     data: 'nominal',
//                     name: 'nominal',
//                 },
        
//             ],
//         });
//     }
// }

let table; // Deklarasikan variabel `table` di sini

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



   