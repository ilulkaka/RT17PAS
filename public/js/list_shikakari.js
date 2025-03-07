$(document).ready(function() {
    bsCustomFileInput.init();
    get_list_shikakari();

    $('.card').on('collapsed.lte.cardwidget', function() {
        $(this).find('.fa-minus').removeClass('fa-minus').addClass('fa-plus');
    });

    $('.card').on('expanded.lte.cardwidget', function() {
        $(this).find('.fa-plus').removeClass('fa-plus').addClass('fa-minus');
    });


    $(document).on('click', '.upd_shikakari', function() {
        // var idNextProcess = $(this).data('id');
        var data = list_shikakari.row($(this).parents('tr')).data();
        var id = data.id_next_process;
        var lotNo = data.lot_no;
        var partNo = data.part_no;
        $("#unp_id").val(id);
        $("#f_lotno").val(lotNo);
        $("#f_partno").val(partNo);
        $("#unp_lotno").html(lotNo);
        $("#unp_sebelum").val(data.cur);

        getlinemodal(data);
        // $("#unp_shikakari").append(new Option(data.nama_line, data.kode_line, true, true));

        $("#unp_shikakari").trigger('change');
        $("#modal_unp").modal('show');

    });

    $("#frm_unp").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serialize();

        $.ajax({
                url: APP_BACKEND + 'produksi/upd_next_process',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                type: 'PATCH',
                dataType: 'json',
                data: datas,
            })
            .done(function(resp) {
                if (resp.success) {
                    Swal.fire({
                        title: 'Update next process',
                        text: resp.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });

                    $('#modal_unp').modal('toggle');
                    list_shikakari.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred: ' + resp.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + errorThrown,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            });
    });



    $("#btn_reload").on("click", function() {
        get_list_shikakari();
    });

    $("#btn_upload").on("click", function() {
        $("#upd_expres").trigger('reset');
        $("#modal_expres").modal('show');
    });

    $("#btn_excel").on("click", function() {
        var conf = confirm("Donwload Shikakari ?");
        if (conf) {
            $.ajax({
                type: "GET",
                url: APP_BACKEND + "produksi/excel_shikakari",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                dataType: "json",

                success: function(response) {
                    var fpath = response.file;
                    window.open(fpath, '_blank');
                }
            });
        }
    });

});

var list_shikakari;
function get_list_shikakari (){
    if ($.fn.DataTable.isDataTable('#tb_detail_shikakari')) {
        list_shikakari.ajax.reload();
    } else {
        list_shikakari = $('#tb_detail_shikakari').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            ajax: {
                url: APP_BACKEND + 'produksi/list_shikakari',
                type: "GET",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                data: function(d) {
                    d.line = $("#selectline").val() || 'All';
                    d.tag = $("#selectTag").val() || 'All';
                    d.warna = $("#selectwarna").val() || 'All';
                },
            },
            columnDefs: [{
    
                    targets: [0],
                    visible: false,
                    searchable: false
                },
                // {
                //     targets: [7],
                //     data: null,
                //     defaultContent: "<button class='btn btn-danger btn-sm'><i class='far fa-trash-alt'></i></button>"
                // }
            ],
    
            columns: [{
                    data: 'id_next_process',
                    name: 'id_next_process'
                },
                {
                    data: 'cur',
                    name: 'cur'
                },
                {
                    data: 'nama_line',
                    name: 'nama_line',
                    render: function(data, type, row) {
                        if (row.dept_section === 'PPIC' || row.dept_section === 'IT' || row.dept_section === 'Admin') {
                            return '<a href="#" style="text-align:center; cursor:pointer" contenteditable="false" class="upd_shikakari" data-id="' +
                                (row.id_next_process || '') + '">' +
                                (data || '') + '</a>';
                        } else {
                            return `<span>${data}</span>`;
                        }
                    }
                },
                {
                    data: 'tgl_in',
                    name: 'tgl_in',
                    className : 'text-center'
                },
                {
                    data: 'part_no',
                    name: 'part_no'
                },
                {
                    data: 'lot_no',
                    name: 'lot_no'
                },
                {
                    data: 'qty_in',
                    name: 'qty_in'
                },
                {
                    data: 'tag',
                    name: 'tag'
                },
                {
                    data: 'warna_tag',
                    name: 'warna_tag'
                },
            ]
        });
    }
}

function getlinemodal (data){
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
            const selectline = $('#unp_shikakari');
            selectline.empty();
            $("#unp_shikakari").append(new Option(data.nama_line, data.kode_line, true, true));

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
}