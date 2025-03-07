$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    getNikCasting();

});

function getNikCasting (){
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
            const selectNikCasting = $('#select_nik_casting');
            const namaOperator = $('#nama_operator');
            selectNikCasting.empty();
            selectNikCasting.append('<option value="">Pilih NIK...</option>');

            resp.datas.forEach(function (nik) {
                selectNikCasting.append('<option value="' + nik.nik + '">' + nik.nik + " / "+ nik.nama + '</option>');
            });

            selectNikCasting.on('change', function () {
                var selectNikCasting = $(this).val();
                var selectedData = resp.datas.find(nik => nik.nik === selectNikCasting);
        
                if (selectedData) {
                    namaOperator.val(selectedData.nama);
                    setTimeout(function() {
                        $("#fehp_sb").focus();
                    }, 100);
                } else {
                    namaOperator.val('');
                }
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