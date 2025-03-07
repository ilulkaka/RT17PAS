$(document).ready(function() {

    getNotifPermintaanSleeve ();

    $("#btn_entry_pouring").on("click", function() {
        window.location.href = APP_URL + "/produksi/frm_foundry/frm_pouring";
    })

    $("#btn_checksheet_elastisitas").on("click", function() {
        window.location.href = APP_URL + "/produksi/frm_foundry/frm_elastisitas";
    })

    $("#btn_permintaan_sleeve").on("click", function() {
        window.location.href = APP_URL + "/produksi/frm_foundry/list_permintaan_sleeve";
    })

    $("#btn_komposisi").on("click", function() {
        window.location.href = APP_URL + "/produksi/frm_foundry/frm_komposisi";
    })
});

function getNotifPermintaanSleeve ()
{
    $.ajax({
        type: "GET",
        url: APP_BACKEND + "foundry/notif_permintaan_sleeve",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: "json",
    })
    .done(function(resp) {
        if (resp.notif_sleeve >= 1) {
            $("#m_notif_sleeve").html(resp.notif_sleeve);
        } else {
            $("#_notif_sleeve").html('');
        }
    })
    .fail(function() {
        $("#error").html(
            "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
        );
    });
}