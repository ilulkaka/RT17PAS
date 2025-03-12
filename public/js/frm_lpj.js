$(document).ready(function() {

    $("#btn_list_lpj").on("click", function() {
        window.location.href = "rpt/list_lpj";
    });

    $("#frm_felk").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();
        $("#btn_submit").prop("disabled", true).text("Processing...");
        $.ajax({
            type: "POST",
            url: APP_BACKEND + "keuangan/ins_lpj",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: datas,
        })
            .done(function(resp) {
                if (resp.success) {
                    fireAlert("success", resp.message);
                    $("#frm_felk")[0].reset();
                    // $("#modal_ew").modal("toggle");
                    // listWarga.ajax.reload(null, false);
                    $("#btn_submit").prop("disabled", false).text("Simpan");
                } else {
                    infoFireAlert("error", resp.message);
                }
            })
    });

});