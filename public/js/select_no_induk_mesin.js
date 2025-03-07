$(document).ready(function() {

    getNomerIndukMesin();

});

function getNomerIndukMesin (){
    $.ajax({
        url: APP_BACKEND + 'mtc/nomer_induk_mesin',
        type: 'GET',
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: 'json',
    })
    .done(function(resp) {
        if (resp.success) {
            const noIndukMesin = $('#nomer_induk_mesin');
            const namaMesin = $('#nama_mesin');
            const noMesin = $('#no_mesin');
            noIndukMesin.empty();
            noIndukMesin.append('<option value="">Pilih No Induk...</option>');

                resp.datas.forEach(function (noInduk) {
                    noIndukMesin.append('<option value="' + noInduk.no_induk + '">' + noInduk.no_induk + '</option>');
                });

            noIndukMesin.on('change', function () {
                var selectedNoInduk = $(this).val();
                var selectedData = resp.datas.find(noInduk => noInduk.no_induk === selectedNoInduk);
        
                if (selectedData) {
                    namaMesin.val(selectedData.nama_mesin);
                    noMesin.val(selectedData.no_urut);
                } else {
                    namaMesin.val('');
                    noMesin.val('');
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