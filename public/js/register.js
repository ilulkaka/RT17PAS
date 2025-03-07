        $(document).ready(function() {
            $(".select2").select2({
                theme: 'bootstrap-5'
            });

            allPermission();

            var listdept = $('#tb_dept').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_BACKEND + 'admin/deptlist',
                    type: "GET",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },

                },
                columnDefs: [{
                        targets: [4],
                        data: null,
                        defaultContent: "<button class='btn btn-edit'><i class='fa fa-edit'></i></button><button class='btn btn-hapus'><i class='fa fa-trash'></i></button>",
                        className: 'text-center',
                    }

                ],

                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'text-center',
                    },
                    {
                        data: 'dept',
                        name: 'dept'
                    },
                    {
                        data: 'group',
                        name: 'group'
                    },
                    {
                        data: 'section',
                        name: 'section'
                    },

                ],
            });

            var listrole = $('#tb_role').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_BACKEND + 'admin/rolelist',
                    type: "GET",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },

                },
                columnDefs: [{
                        targets: [3],
                        data: null,
                        defaultContent: "<button class='btn btn-edit'><i class='fa fa-edit'></i></button><button class='btn btn-hapus'><i class='fa fa-trash'></i></button>"
                    }

                ],

                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'text-center',
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },

                ],
            });

            var listpermit = $('#tb_permission').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: APP_BACKEND + 'admin/permissionlist',
                    type: "GET",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + key);
                    },

                },
                columnDefs: [{
                        targets: [2],
                        data: null,
                        defaultContent: "<button class='btn btn-edit'><i class='fa fa-edit'></i></button><button class='btn btn-hapus'><i class='fa fa-trash'></i></button>"
                    }

                ],

                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'text-center',
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },

                ],
            });
            var edit = false;
            // ==================================================Department
            $("#btn-add-dept").click(function() {
                $("#deptModalLabel").html("Tambah Department");
                edit = false;
                $("#deptModal").modal("toggle");
            });
            $("#frm-dept").submit(function(e) {
                e.preventDefault();
                var d = $(this).serialize();
                if (edit) {
                    var id = $("#id_dept").val();
                    $.ajax({
                        url: APP_BACKEND + "admin/edit_dept",
                        type: 'PUT',
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + key);
                        },
                        data: d,
                    }).done(function(resp) {
                        $("#deptModal").modal("toggle");
                        fireAlert("success", resp.message);
                        listdept.ajax.reload();
                        //location.reload();
                    });
                } else {

                    action_data(APP_BACKEND + "admin/createdept", d, key).done(function(resp) {
                        if (resp.success) {
                            $("#deptModal").modal("toggle");
                            fireAlert("success", resp.message);
                            listdept.ajax.reload();
                        } else {
                            fireAlert("error", resp.message);
                        }
                    });
                }
            });

            //hapus dept
            $("#tb_dept").on("click", ".btn-hapus", function() {

                var data = listdept.row($(this).parents("tr")).data();


                Swal.fire({
                    title: "Apakah anda akan menghapus data ini?",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    confirmButtonColor: "#d33",
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: APP_BACKEND + "admin/del_dept/" + data.id,
                            type: 'DELETE',
                            dataType: 'json',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader("Authorization", "Bearer " + key);
                            },
                        }).done(function(resp) {
                            fireAlert("success", resp.message);
                            listdept.ajax.reload();
                            //location.reload();
                        });
                    }
                });

            });

            $("#tb_dept").on("click", ".btn-edit", function() {
                $("#deptModalLabel").html("Edit Department");
                edit = true;
                var data = listdept.row($(this).parents("tr")).data();
                $("#id_dept").val(data.id);
                $("#dept").val(data.dept);
                $("#group").val(data.group);
                $("#section").val(data.section);
                $("#jenis").val(data.jenis);
                $("#deptModal").modal("toggle");
            });

            // ============================================permissions====

            $("#btn-add-permit").click(function() {
                edit = false;
                $("#permitModal").modal("toggle");
            });

            $("#frm-permit").submit(function(e) {
                e.preventDefault();
                var d = $(this).serialize();
                if (edit) {

                    $.ajax({
                        url: APP_BACKEND + "admin/edit_permit",
                        type: 'PUT',
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + key);
                        },
                        data: d,
                    }).done(function(resp) {
                        $("#permitModal").modal("toggle");
                        if (resp.success) {

                            fireAlert("success", resp.message).then(function() {
                                location.reload();
                            });
                        } else {
                            fireAlert("error", resp.message);
                        }

                    });
                } else {

                    action_data(APP_BACKEND + "admin/createpermit", d, key).done(function(resp) {
                        if (resp.success) {
                            $("#permitModal").modal("toggle");
                            fireAlert("success", resp.message).then(function() {
                                location.reload();
                            });
                        } else {
                            fireAlert("error", resp.message);
                        }
                    });
                }
            });
            $("#tb_permission").on("click", ".btn-edit", function() {
                $("#permitModalLabel").html("Edit Permission");
                edit = true;
                var data = listpermit.row($(this).parents("tr")).data();
                $("#id_permit").val(data.id);
                $("#inputpermit").val(data.name);
                $("#permitModal").modal("toggle");
            });
            $("#tb_permission").on("click", ".btn-hapus", function() {

                var data = listpermit.row($(this).parents("tr")).data();


                Swal.fire({
                    title: "Apakah anda akan menghapus data ini?",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    confirmButtonColor: "#d33",
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: APP_BACKEND + "admin/del_permit/" + data.id,
                            type: 'DELETE',
                            dataType: 'json',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader("Authorization", "Bearer " + key);
                            },
                        }).done(function(resp) {
                            if (resp.success) {

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

            // ===============================================Role

            $("#btn-add-role").click(function() {
                edit = false;
                $("#roleModal").modal("toggle");
            });

            $("#frm-role").submit(function(e) {
                e.preventDefault();
                var d = $(this).serialize();
                if (edit) {
                    var id = $("#id_role").val();
                    $.ajax({
                        url: APP_BACKEND + "admin/edit_role",
                        type: 'PUT',
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + key);
                        },
                        data: d,
                    }).done(function(resp) {
                        $("#roleModal").modal("toggle");
                        if (resp.success) {

                            fireAlert("success", resp.message).then(function() {
                                location.reload();
                            });
                        } else {
                            fireAlert("error", resp.message);
                        }

                    });
                } else {

                    action_data(APP_BACKEND + "admin/createrole", d, key).done(function(resp) {
                        if (resp.success) {
                            $("#roleModal").modal("toggle");
                            fireAlert("success", resp.message).then(function() {
                                location.reload();
                            });
                        } else {
                            fireAlert("error", resp.message);
                        }
                    });
                }
            });

            listrole.on("click", "td.dt-control", function(e) {
                let tr = e.target.closest('tr');
                let row = listrole.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                } else {

                    row.child(format(row.data().permissions)).show();
                }
            });

            listrole.on("click", ".btn-edit", function() {
                $("#roleModalLabel").html("Edit Role");
                edit = true;
                var data = listrole.row($(this).parents("tr")).data();
                var sel_id = [];
                for (let i = 0; i < data.permissions.length; i++) {
                    sel_id.push(data.permissions[i].id);

                }
                $("#id_role").val(data.id);
                $("#inputrole").val(data.name);
                $("#permit").val(sel_id).trigger("change");
                $("#roleModal").modal("toggle");
            });

            listrole.on("click", ".btn-hapus", function() {
                var data = listrole.row($(this).parents("tr")).data();


                Swal.fire({
                    title: "Apakah anda akan menghapus data ini?",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    confirmButtonColor: "#d33",
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: APP_BACKEND + "admin/del_role/" + data.id,
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
                            listrole.ajax.reload();

                        });
                    }
                });
            });

        });

        function action_data(url, datas, key) {
            return $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: datas,
            });
        }

        function format(d) {
            var newrow = ""
            for (let i = 0; i < d.length; i++) {
                newrow = newrow + "<tr><td>" + d[i].name + "</td></tr>";

            }
            return newrow;
        }

        function allPermission() {
            $.ajax({
                url: APP_BACKEND + "admin/allpermission",
                type: 'GET',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
            }).done(function(resp) {
                if (resp.success) {
                    var sel = select = document.getElementById('permit');
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