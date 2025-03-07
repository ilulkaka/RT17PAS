var APP_URL = window.location.origin;
var APP_BACKEND = APP_URL+'/api/';
var key = localStorage.getItem('npr_token');

function fireAlert(jenis,msg){
    return Swal.fire({
        position: "center",
        type: jenis,
        title: msg,
        showConfirmButton: false,
        timer: 1500
        });
};

function infoFireAlert(jenis,msg){
    return Swal.fire({
        position: "center",
        type: jenis,
        title: "<span style='font-family: Arial; font-size: 20px; font-weight: bold; '>"+msg+"</span>",
        showConfirmButton: true,
    });
};


$(document).ready(function() {
    //var APP_URL = {!! json_encode(url('/')) !!}
    $("#btn_logout").click(function(e){
        e.preventDefault();
        var user = localStorage.getItem('npr_id_user');
        $.ajax({
                url: APP_URL + '/logout',
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // data: {
                //     id: user
                // },
            })
            .done(function(resp) {
                if (resp.success) {
                    localStorage.removeItem('npr_name');
                    localStorage.removeItem('npr_token');
                    localStorage.removeItem('npr_id_user');
                    window.location.href = APP_URL+"/login";

                } else
                    $("#error").html("<div class='alert alert-danger'><div>Error</div></div>");
            })
            .fail(function() {
                $("#error").html(
                    "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                );
                //toastr['warning']('Tidak dapat terhubung ke server !!!');
            });
    })

});

function spinner_on (){
    $("#spinner-overlay").removeClass('d-none');
}

function spinner_off (){
    $("#spinner-overlay").addClass('d-none');
}