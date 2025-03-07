        $(document).ready(function() {
            $(".select2").select2({
                theme: 'bootstrap-5'
            });

            allDept();
            allRole();
            var edit = false;
            var listuser = $('#tb_user').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_BACKEND + 'admin/userlist',
                    type: "GET",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },

                },
                columnDefs: [{
                        targets: [6],
                        data: null,
                        defaultContent: "<button class='btn btn-edit'><i class='fa fa-edit'></i></button><button class='btn btn-hapus'><i class='fa fa-trash'></i></button>"
                    }

                ],

                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'setatus',
                        name: 'setatus'
                    },
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },

                ],
            });

            $("#btn-add-user").click(function() {
                edit = false;
                $("#btn-reset").hide();
                $("#passuser").prop("required", true);
                $("#customSwitch1").prop("checked", true);
                $("#userModalLabel").html("Tambah User");
                $("#nikuser").prop("disabled", false);
                $("#userModal").modal("toggle");
            });

            $("#tb_user").on('click', '.btn-hapus', function() {
                var data = listuser.row($(this).parents('tr')).data();

                Swal.fire({
                    title: "Apakah anda akan menghapus data ini?",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    confirmButtonColor: "#d33",
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: APP_BACKEND + "admin/del_user/" + data.id,
                            type: 'DELETE',
                            dataType: 'json',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader("Authorization", "Bearer " + key);
                            },
                        }).done(function(resp) {
                            if (resp.success) {

                                fireAlert("success", resp.message);
                            } else {
                                fireAlert("error", resp.message);
                            }
                            listuser.ajax.reload();

                        });
                    }
                });

            });

            $("#tb_user").on('click', '.btn-edit', function() {
                edit = true;
                var data = listuser.row($(this).parents('tr')).data();
                var sel_dept = [];
                for (let i = 0; i < data.departments.length; i++) {
                    sel_dept.push(data.departments[i].id);

                }
                var sel_role = [];
                for (let i = 0; i < data.roles.length; i++) {
                    sel_role.push(data.roles[i].id);

                }
                if (data.setatus == "on") {
                    $("#customSwitch1").prop("checked", true);
                } else {
                    $("#customSwitch1").prop("checked", false);
                }
                $("#userModalLabel").html("Edit User");
                $("#btn-reset").show();
                $("#id_user").val(data.id);
                $("#nikuser").val(data.nik);
                $("#nikuser").prop("disabled", true);
                $("#emailuser").val(data.email);
                $("#inputuser").val(data.name);
                $("#department").val(sel_dept).trigger("change");
                $("#role").val(sel_role).trigger("change");
                $("#userModal").modal("toggle");
                $("#passuser").prop("required", false);
                // window.location.href = APP_URL + "/user-edit/edit/" + data.id;
            });

            listuser.on("click", "td.dt-control", function(e) {
                let tr = e.target.closest('tr');
                let row = listuser.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();

                } else {

                    row.child(format(row.data().departments, row.data().roles)).show();

                }
            });

            $("#nikuser").keypress(function (e) {
                var nik = $("#nikuser").val(); 
            
                if (e.keyCode === 13 && nik) {
                    getUserByNIK(nik);
                }
            });

            $("#frm-user").submit(function(e) {
                e.preventDefault();
                var d = $(this).serialize();

                if (edit) {

                    $.ajax({
                        url: APP_BACKEND + "admin/edit_user",
                        type: 'PUT',
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + key);
                        },
                        data: d,
                    }).done(function(resp) {
                        $("#userModal").modal("toggle");
                        if (resp.success) {

                            fireAlert("success", resp.message).then(function() {
                                location.reload();
                            });
                        } else {
                            fireAlert("error", resp.message);
                        }

                    });
                } else {

                    action_data(APP_BACKEND + "admin/createuser", "POST", d, key).done(function(resp) {
                        if (resp.success) {
                            $("#userModal").modal("toggle");
                            fireAlert("success", resp.message).then(function() {
                                location.reload();
                            });
                        } else {
                            fireAlert("error", resp.message);
                        }
                    });
                }

            });

            $("#btn-reset").click(function() {
                var pass = $("#passuser").val();
                var id_user = $("#id_user").val();
                if (!pass == "" || !pass == null) {
                    var d = {
                        id: id_user,
                        password: pass
                    }
                    action_data(APP_BACKEND + "admin/resetpassword", "POST", d, key).done(function(resp) {
                        if (resp.success) {
                            $("#userModal").modal("toggle");
                            fireAlert("success", resp.message).then(function() {
                                location.reload();
                            });
                        } else {
                            fireAlert("error", resp.message);
                        }
                    });
                }
            });


        });

        function action_data(url, metode, datas, key) {
            return $.ajax({
                url: url,
                type: metode,
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: datas,
            });
        }

        function allDept() {

            action_data(APP_BACKEND + "admin/alldept", "GET", null, key).done(function(resp) {
                if (resp.success) {
                    var sel = select = document.getElementById('department');
                    for (var p in resp.data) {
                        var opt = document.createElement('option');
                        opt.value = resp.data[p].id;
                        opt.innerHTML = resp.data[p].section;
                        sel.appendChild(opt);
                    }
                } else {
                    fireAlert("error", resp.message);
                }
            });
        }

        function allRole() {
            action_data(APP_BACKEND + "admin/allrole", "GET", null, key).done(function(resp) {
                if (resp.success) {
                    var sel = select = document.getElementById('role');
                    for (var p in resp.data) {
                        var opt = document.createElement('option');
                        opt.value = resp.data[p].id;
                        opt.innerHTML = resp.data[p].name;
                        sel.appendChild(opt);
                    }
                } else {
                    fireAlert("error", resp.message);
                }
            });
        }

        function format(d, r) {
            var newrow = "<dl style='background-color:#fff2e6'><dt>Departments : </dt>"
            for (let i = 0; i < d.length; i++) {
                newrow = newrow + "<dd>" + d[i].section + "</dd>";

            }
            newrow = newrow + "<dt>Roles : </dt>"
            for (let i = 0; i < r.length; i++) {
                newrow = newrow + "<dd>" + r[i].name + "</dd>";

            }
            newrow = newrow + "</dl>"
            return newrow;
        }

        function getUserByNIK(nik) {
            $.ajax({
                url: APP_BACKEND + 'admin/getUserByNik/' + nik,
                type: "GET",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.success) {
                        $('#inputuser').val(resp.datas.nama);
                        $('#emailuser').val(resp.datas.email);
                    } else {
                        alert(resp.message);
                        $("#frm-user").trigger('reset');
                        $('#nikuser').focus();
                    }
                }
                ,
                error: function() {
                    alert("Terjadi kesalahan dalam mengambil data");
                }
            });
        }