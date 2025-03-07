
        $(document).ready(function() {

            $('.select2').select2({
                theme: 'bootstrap-5'
            });

                $.ajax({
                    url: APP_BACKEND + 'produksi/getline',
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },
                    dataType: 'json',
                })
                .done(function(resp) {
                    if (resp.success) {
                        const selectline = $('#selectline');
                        selectline.empty();
                        selectline.append('<option value="">Pilih Line...</option>');
        
                        selectline.append('<option value="All">All</option>');
                        resp.data.forEach(function (line) {
                            selectline.append('<option value="' + line.kode_line + '">' + line.nama_line + '</option>');
                        });
        
                        selectline.trigger('change');

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
