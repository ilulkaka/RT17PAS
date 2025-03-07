$(document).ready(function() {
    var APP_URL = window.location.origin;

    $("#login-form").submit(function(event) {
        event.preventDefault();
        var data = $(this).serialize();
        var btn = $("#btn-login");
        btn.html('Sign In');
        btn.attr('disabled', true);
        $.ajax({
                url: APP_URL + '/postlogin',
                type: 'POST',
                dataType: 'json',
                data: data,
            })
            .done(function(resp) {
                if (resp.success) {
                    localStorage.setItem('npr_token', resp.token);
                    localStorage.setItem('npr_name', resp.user.name);
                    localStorage.setItem('npr_id_user', resp.user.id);
                    // console.log(resp.message);
                    window.location.href = APP_URL;
                } else {
                    $("#error").html("<div class='alert alert-danger'><div>" +
                        resp.message + "</div></div>");
                }
            })
            .fail(function() {
                $("#error").html(
                    "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                );
                //toastr['warning']('Tidak dapat terhubung ke server !!!');
            })
            .always(function() {
                btn.html('Login');
                btn.attr('disabled', false);
            });

        return false;
    });
});