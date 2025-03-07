$(document).ready(function() {

    $('.select2').select2({
        theme: 'bootstrap-5'
    });

        $.ajax({
            url: APP_BACKEND + 'produksi/getwarna',
            type: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            dataType: 'json',
        })
        .done(function(resp) {
            if (resp.success) {
                const selectwarna = $('#selectwarna');
                selectwarna.empty();
                selectwarna.append('<option value="">Pilih warna...</option>');

                selectwarna.append('<option value="All">All</option>');
                resp.data.forEach(function (warna) {
                    selectwarna.append('<option value="' + warna.warna_tag + '">' + warna.warna_tag + '</option>');
                });

                selectwarna.trigger('change');

            } else {
                $("#error").html("<div class='alert alert-danger'><div>Error</div></div>");
            }

        })
        .fail(function() {
            $("#error").html(
                "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
            );
        });

});