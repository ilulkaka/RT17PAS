$(document).ready(function() {

    var ctx = document.getElementById('chartlembur').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {

        },
        options: {}
    });

    var awal = $("#tgl_awal").val();
    var akhir = $("#tgl_akhir").val();

    tampil_chart(awal, akhir, chart);

    $("#btn_reload").on("click", function() {
        var awal = $("#tgl_awal").val();
        var akhir = $("#tgl_akhir").val();

        tampil_chart(awal, akhir, chart);
    });

    $("#btn_tambah").click(function() {
        window.location.href = APP_URL + "/manager/targetlembur";
    })

    var peri = $("#periode").val();
    get_planning_lembur(peri);

    $("#periode").on("change", function() {
        var now = $('#periode').val();
        get_planning_lembur(now, key);
    });

    $("#frm_pl").on("submit", function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
                url: APP_BACKEND + 'pga/create_planning_lembur',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + key);
                },
                type: 'POST',
                dataType: 'json',
                data: data,
            })
            .done(function(resp) {
                if (resp.success) {

                    alert(resp.message);
                    location.reload();
                } else
                    alert(resp.message);
            })
            .fail(function() {
                $("#error").html(
                    "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
                );

            })
            .always(function() {});
    })

    $("#pl_awal").on("change", function() {
        var awal = $("#pl_awal").val();
        var akhir = $("#pl_akhir").val();
        var tot = (Number(awal) + Number(akhir));
        $("#pl_total").val(tot);
    })

    $("#pl_akhir").on("change", function() {
        var awal = $("#pl_awal").val();
        var akhir = $("#pl_akhir").val();
        var tot = (Number(awal) + Number(akhir));
        $("#pl_total").val(tot);
    })


});







function tampil_chart(awal, akhir, chart) {
    $.ajax({
        url: APP_BACKEND + "PGA/grafik_lembur",
        method: "GET",
        data: {
            "tgl_awal": awal,
            "tgl_akhir": akhir
        },
        dataType: "json",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        success: function(data) {
            var label = [];
            var value = [];
            var Tvalue = [];
            var Mvalue = [];
            var jm = 0;
            var tot = 0;
            var tot2 = 0;
            var jmlmem = 0;
            var gab = 0;
            var j_ob = 0;
            var j_it = 0;
            var j_tech = 0;
            var p_tech = 0;
            var it = 0;
            var mem_gab = 0;
            var mem = 0;
            var totmember = 0;

            $("#tb_lembur tbody").empty();
            $("#tb_lembur tfoot").empty();

            for (var i in data) {
                label.push(data[i].dept_section);
                value.push(data[i].total_jam);
                Tvalue.push(data[i].total_target_jam);
                Mvalue.push(data[i].total_member);
                sec = data[i].dept_section;
                jm = Number(data[i].total_jam);
                jm2 = Number(data[i].total_target_jam);
                mem = Number(data[i].total_member);

                sel = jm2 - jm;
                totmem = Number(jm2) / Number(mem);
                totmem1 = Number(totmem.toFixed(2));

                if (sel < 0) {
                    td = '<td style="color: rgb(150, 7, 7);">' + sel.toFixed(2) + '</td>';
                } else {
                    td = '<td style="color: rgb(7, 150, 7);">' + sel.toFixed(2) + '</td>';
                }


                tot = tot + Number(jm)
                tot2 = tot2 + Number(jm2)
                jmlmem = jmlmem + Number(mem);

                // if (gab > 0) {
                var newrow = '<tr><td>' + sec + '</td><td>' + Number(jm)
                    .toLocaleString("en-US") +
                    '</td><td>' + Number(jm2).toLocaleString("en-US") + '</td>' + td.toLocaleString(
                        "en-US") + '<td>' + mem + '</td>' +
                    '<td>' + totmem1.toLocaleString("en-US") + '</td></tr>';

                $('#tb_lembur tbody').append(newrow);
                // }
            }
            var p = tot2 - tot;
            var p1 = tot2 / jmlmem;

            $("#tb_lembur tfoot").append('<tr><th>Total :</th><th>' + Number(tot)
                .toLocaleString(
                    "en-US") + '</th><th>' +
                tot2.toLocaleString("en-US") + '</th><th>' + p.toLocaleString(
                    "en-US") + '</th>' + '<th>' + jmlmem.toLocaleString("en-US") + '</th>' +
                '<th>' + p1.toLocaleString(
                    "en-US") + '</th></tr>')

            chart.data = {
                labels: label,
                datasets: [{
                        label: 'Quota Lembur',
                        fill: false,
                        borderColor: 'rgb(255, 153, 51)',
                        pointBorderWidth: 3,
                        lineTension: 0.2,
                        data: Tvalue,
                        type: 'line'
                    },
                    {
                        label: 'Jam Lembur',
                        backgroundColor: 'rgb(51, 153, 255)',
                        borderColor: 'rgb(51, 153, 255)',
                        data: value,
                    }

                ]
            };
            chart.options = {

                onClick: function(e, context) {
                    // e = i[0];
                    // console.log(context[0].index);
                    // console.log(chart.data.labels[context[0].index]);
                    var x_value = chart.data.labels[context[0].index];
                    var y_value = this.data.datasets[0].data[e._index];

                    get_detail_lembur(x_value, awal, akhir);
                    $("#tgl-awal").html(awal);
                    $("#tgl-akhir").html(akhir);
                    $('#ModalLongTitle').html(x_value);
                    $('#detail-modal').modal('show');

                }
            };
            chart.update();
        }
    });
}



function get_detail_lembur(kel, awal, akhir) {
    var list_detail_lembur = $("#tb_detail_lembur").DataTable({

        destroy: true,
        processing: true,
        serverSide: true,
        searching: true,
        ordering: false,
        ajax: {
            url: APP_BACKEND + 'PGA/detail_lembur',
            type: "GET",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: {
                "dept": kel,
                "awal": awal,
                "akhir": akhir
            },

        },
        columns: [

            {
                data: 'NIK',
                name: 'NIK'
            },
            {
                data: 'NAMA',
                name: 'NAMA'
            },
            {
                data: 'SECTION',
                name: 'SECTION'
            },
            {
                data: 'nama_jabatan',
                name: 'nama_jabatan'
            },
            {
                data: 'total_jam',
                name: 'total_jam'
            },

        ]
    });
}

function get_planning_lembur(periode) {
    $.ajax({
        url: APP_BACKEND + "PGA/planning_lembur",
        method: "GET",
        data: {
            "periode": periode
        },
        dataType: "json",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        success: function(data) {
            var label = [];
            var value = [];
            var value2 = [];
            var jm = 0;
            var temp_1 = 0;
            var temp_2 = 0;

            $("#tb_cek_lembur tbody").empty();
            $("#tb_cek_lembur tfoot").empty();

            for (var i in data) {
                awal = data[i].pertama;
                akhir = data[i].kedua;
                jm = data[i].target_jam;
                temp_1 = data[i].temp_1;
                temp_2 = data[i].temp_2;
                status_lembur = data[i].status_lembur;
                if (jm == null) {
                    cek = "<i class='fas fa-ban' style='color:red'></i>";
                    e_lembur = "";
                } else {
                    cek = "<i class='fas fa-check' style='color:green'></i>";
                    e_lembur = "<i class='far fa-edit' style='color:red'></i>";
                }

                if (temp_1 == null) {
                    t_1 = 0;
                } else {
                    t_1 = data[i].temp_1;
                }

                if (temp_2 == null) {
                    t_2 = 0;
                } else {
                    t_2 = data[i].temp_2;
                }

                var newrow = '<tr><td><a href="#" id="' + data[i]
                    .kode_section + '" data-dept ="'+ data[i].dept + '" class="p_lembur">' + data[i].section +
                    '</td><td><input type ="hidden" name="section_name[]">' + Number(awal)
                    .toLocaleString(
                        "en-US") +
                    '</td><td><input type ="hidden" name="section_name[]">' + Number(akhir)
                    .toLocaleString(
                        "en-US") +
                    '</td><td><a href="#" id="' + data[i].id_number + '" data-dept ="'+ data[i].dept + '" class="e_lembur">' +
                    e_lembur +
                    '</td><td style="display: none;"><input type="hidden">' + Number(t_1) +
                    '</td><td style="display: none;"><input type="hidden">' + Number(t_2) +
                    '</td><td style="display: none;"><input type="hidden">' + status_lembur +
                    '</td><td style="display: none;"><input type="hidden">' + Number(jm).toLocaleString(
                        "en-US") +
                    '</td><td style="display: none;"><input type="hidden">' + data[i].kode_section +
                    '</td></td></tr>';

                $('#tb_cek_lembur tbody').append(newrow);
            }
        }

    });
}

$("#tb_cek_lembur").on("click", ".p_lembur", function(e) {
    // alert($("#periode").val());
    e.preventDefault();
    var kode_section = this.id;
    var currentRow = $(this).closest("tr");
    var section = currentRow.find("td:eq(0)").text(); // get current row 3rd TD
    var dept = $(this).data('dept');
    // alert(operator);
    $("#frm_pl").trigger('reset');

    $("#n_dept").val(section);
    $("#pl_kode").val(kode_section);
    $("#pl_periode").val($("#periode").val());
    $("#pl_sect").val(section);
    $("#pl_dept").val(dept);

    $("#modal_pl").modal('show');
});

$("#tb_cek_lembur").on("click", ".e_lembur", function(e) {
    e.preventDefault();

    var selectedPeriode = $("#periode").val();

    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = currentDate.getMonth() + 1; // getMonth() mengembalikan indeks bulan (0 - 11)
    month = month < 10 ? '0' + month : month; // Tambahkan 0 di depan jika bulan kurang dari 10

    var formattedDate = year + '-' + month;

    if (selectedPeriode >= formattedDate) {
        var id = this.id;
        var currentRow = $(this).closest("tr");
        var section = currentRow.find("td:eq(0)").text();
        var dept = $(this).data('dept');

        var st2 = currentRow.find("td:eq(1)").text(); // get current row 3rd TD
        var st1 = st2.replace(/,/g, ''); // Menghapus semua koma dari teks
        var st = parseInt(st1);
        var nd2 = currentRow.find("td:eq(2)").text();
        var nd1 = nd2.replace(/,/g, '');
        var nd = parseInt(nd1);

        var temp1 = currentRow.find("td:eq(4)").text();
        var temp2 = currentRow.find("td:eq(5)").text();
        var l_status = currentRow.find("td:eq(6)").text();

        if (l_status == 'Permohonan Edit') {
            l_s = 'Menunggu Approve';
        } else if (l_status == 'Edited') {
            l_s = 'Edited';
        } else {
            l_s = '';
        }

        var tot_jam = st + nd;
        var kode_dept = currentRow.find("td:eq(8)").text();
        var tot_tt = Number(temp1) + Number(temp2);

        $("#etl_id").val(id);
        $("#etl_dept").val(dept);
        $("#etl_section").val(section);
        // $("#etl_kode").val(id);
        $("#etl_awal").val(st);
        $("#etl_akhir").val(nd);
        $("#etl_total").val(tot_jam);

        $("#etl_temp1").val(temp1);
        $("#etl_temp2").val(temp2);
        $("#etl_total_temp").val(tot_tt);

        $("#l_status").html(l_s);

        $("#modal_etl").modal('show');
    } else {
        alert("Periode " + selectedPeriode + " Sudah selesai .");
    }
});

$("#etl_temp1").on("change", function() {
    var temp1 = $("#etl_temp1").val();
    var temp2 = $("#etl_temp2").val();
    var tot = (Number(temp1) + Number(temp2));
    $("#etl_total_temp").val(tot);
})

$("#etl_temp2").on("change", function() {
    var temp1 = $("#etl_temp1").val();
    var temp2 = $("#etl_temp2").val();
    var tot = (Number(temp1) + Number(temp2));
    $("#etl_total_temp").val(tot);
})

$("#frm_etl").on("submit", function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    // alert(data);

    $.ajax({
            url: APP_BACKEND + 'pga/edit_planning_lembur',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            type: 'POST',
            dataType: 'json',
            data: data,
        })
        .done(function(resp) {
            if (resp.success) {

                alert(resp.message);
                location.reload();
            } else {
                alert(resp.message);
            }
        })
        .fail(function() {
            $("#error").html(
                "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
            );

        })
        .always(function() {});
})