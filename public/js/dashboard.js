$(document).ready(function(){
    $('.select2').select2({
        theme: 'bootstrap4',
        // tags: true,
    });

    get_dataDashboard(key);
    

});


function get_dataDashboard(key){
    $("#saldo").html('Loading...');
    $("#pemasukan_bulan_ini").html('Loading...');
    $('#pengeluaran_bulan_ini').html(`Loading...`);
    $("#warga_terdaftar").html('Loading...');
    $.ajax({
        url: APP_BACKEND + "dashboard/get_dataDashboard",
        method: "GET",
        dataType: "json",
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },

        // =========== Hasil Produksi, Sales dan Lembur ==========
        success: function(resp) {
            let saldo = parseFloat(resp.saldo);
            let pemasukanBulanIni = parseFloat(resp.pemasukanBulanIni);
            let pengeluaranBulanIni = parseFloat(resp.pengeluaranBulanIni);
            let wargaTerdaftar = parseFloat(resp.wargaTerdaftar);

            if (isNaN(saldo)) {
                saldo = 0;
            }

            if (isNaN(wargaTerdaftar)) {
                wargaTerdaftar = 0;
            }

            if (isNaN(pemasukanBulanIni)) {
                pemasukanBulanIni = 0;
            }

            if (isNaN(pengeluaranBulanIni)) {
                pengeluaranBulanIni = 0;
            }


            $("#saldo").html('Rp ' + saldo.toLocaleString());
            $("#pemasukan_bulan_ini").html('Rp ' + pemasukanBulanIni.toLocaleString());
            $('#pengeluaran_bulan_ini').html(`Rp ${pengeluaranBulanIni.toLocaleString()}`);
            $("#warga_terdaftar").html(wargaTerdaftar.toLocaleString() + ' Warga');

        },

        error: function(xhr, status, error) {
            console.error("Error: " + error);
        }
    })
}