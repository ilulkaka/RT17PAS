
        $(document).ready(function() {

                $.ajax({
                    url: APP_BACKEND + 'keuangan/get_blok',
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: 'json',
                })
                .done(function(resp) {
                    if (resp.success) {
                        const selectblok = $('#selectblok');
                        selectblok.empty();
                        // selectblok.append('<option value="">Pilih Blok...</option>');
        
                        selectblok.append('<option value="All">All</option>');
                        resp.data.forEach(function (blok) {
                            selectblok.append('<option value="' + blok.blok + '">' + blok.blok + '</option>');
                        });
        
                        selectblok.trigger('change');

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
