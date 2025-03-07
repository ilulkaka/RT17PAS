$(document).ready(function(){
    get_rekap_produksiNsales();

});

var start = moment().startOf('month').format('YYYY-MM-DD');
var end = moment().format('YYYY-MM-DD');

$('#daterange').daterangepicker({
    startDate: start,
    endDate: end,
    locale: {
        format: 'YYYY-MM-DD'
    }
});

$("#btn_find").click(function(e) {
    e.preventDefault();
    var startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');

    load_table(startDate, endDate, "tb_hasil_produksi", "Daily");
});

$("#btn_findGrouping").click(function(e) {
    e.preventDefault();
    var daterange = $('#daterangeGrouping').data('daterangepicker');
    var startDate = daterange.startDate.format('YYYY-MM-DD');
    var endDate = daterange.endDate.format('YYYY-MM-DD');
    var line = $("#selectline").val();

    if (line == null || line == '') {
        alert('Select Line .');
        return '';
    } else {
        load_table(startDate, endDate, "tb_grouping", "Grouping", line);
    }

})

function load_table(tgl, tgl2, tabl, jenis, line) {
    $.ajax({
        url: APP_BACKEND + "produksi/get_rekapHasilProduksi",
        type: "GET",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: "json",
        data:{'tgl':tgl, 'tgl2':tgl2, 'tabl':tabl, 'jenis':jenis, 'line':line}
    })

    .done(function(resp) {
        if(jenis == 'Daily'){
            displayRekapHasilProduksi(resp);
        } else if (jenis == 'Grouping'){
            displayRekapHasilProduksiGrouping(resp);
        }
    }).fail(function() {
        alert("error");
    });
}

$('#daterangeGrouping').daterangepicker({
    startDate: start,
    endDate: end,
    locale: {
        format: 'YYYY-MM-DD'
    }
});

function get_rekap_produksiNsales(){
    $.ajax({
        url: APP_BACKEND + "produksi/get_rekap_produksiNsales",
        type: "GET",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        dataType: "json",
    })
    .done(function(resp) {
       $("#desc_produksi").text(resp.target[0].process_cd);
      var targetProduksi = parseFloat(resp.target[0].target);
      var aktualProduksi = parseFloat(resp.aktualProduksi);
      var diffProduksi = aktualProduksi - targetProduksi;

      $("#target_produksi").text(Number(targetProduksi).toLocaleString("en-US"));
      $("#aktual_produksi").text(Number(aktualProduksi).toLocaleString("en-US"));
      $("#diff_produksi").text(Number(diffProduksi).toLocaleString("en-US"));

        if (diffProduksi < 0) {
            $("#diff_produksi").css("color", "red"); 
        } else if (diffProduksi > 0) {
            $("#diff_produksi").css("color", "green"); 
        } else {
            $("#diff_produksi").css("color", "black"); 
        }

      var targetSales = parseFloat(resp.target[1].target);
      var aktualSales = parseFloat(resp.aktualSales);
      var diffSales = aktualSales - targetSales;

       $("#desc_sales").text(resp.target[1].process_cd);
       $("#target_sales").text(Number(targetSales).toLocaleString("en-US"));
       $("#aktual_sales").text(Number(aktualSales).toLocaleString("en-US"));
       $("#diff_sales").text(Number(diffSales).toLocaleString("en-US"));

       if (diffSales < 0) {
        $("#diff_sales").css("color", "red"); 
        } else if (diffSales > 0) {
            $("#diff_sales").css("color", "green"); 
        } else {
            $("#diff_sales").css("color", "black"); 
        }


        // ===== sales all =====
        displayHasilSales(resp);

        // ===== Amount =====
        displayHasilAmount(resp);

    })
    .fail(function() {
        $("#error").html(
            "<div class='alert alert-danger'><div>Tidak dapat terhubung ke server !!!</div></div>"
        );

    });
}

function displayHasilSales(salesproduksi) {
    var htmlContent = '';
    
    $.each(salesproduksi.salesproduksi, function(index, salesproduksi) {
        var cur_cd = salesproduksi.cur_cd;
        var textColor = cur_cd === 'USD' ? 'blue' : 'black';
        htmlContent += '<tr>';
        htmlContent += '<td style="color:' + textColor + ';">' + salesproduksi.ofcl_nm + '</td>';
        htmlContent += '<td style="color:' + textColor + ';">' + Number(salesproduksi.qty).toLocaleString("en-US") + '</td>';
        htmlContent += '<td style="color:' + textColor + ';">' + salesproduksi.unit_cd + '</td>';
        htmlContent += '<td style="color:' + textColor + ';">' + Number(salesproduksi.amt).toLocaleString("en-US") + '</td>';
        htmlContent += '<td style="color:' + textColor + ';">' + cur_cd + '</td>';
        htmlContent += '</tr>';
    });
    
    $('#hasil_allSales').html(htmlContent); 
}

function displayHasilAmount (totalamount){
    var htmlContent = '';

    $.each(totalamount.totalamount, function(index, totalamount) {
    htmlContent += '<tr>';
    htmlContent += '<td>' + totalamount.cur_cd + '</td>';
    htmlContent += '<td>' + Number(totalamount.qty).toLocaleString("en-US") + '</td>';
    htmlContent += '<td>' + Number(totalamount.amt).toLocaleString("en-US") + '</td>';
    htmlContent += '</tr>';
    });

    $('#hasil_amount').html(htmlContent); 
}

function displayRekapHasilProduksi(hasilproduksi) {
    var htmlContent = '';
    $.each(hasilproduksi.hasilproduksi, function(index, hasilproduksi) {
        var diffRekapHasilProduksi = Number(hasilproduksi.target_qty)- Number(hasilproduksi.finish_qty)
        var textColor = diffRekapHasilProduksi < 0 ? 'red' : 'green';
        htmlContent += '<tr>';
        htmlContent += '<td>' + hasilproduksi.nama_line + '</td>';
        htmlContent += '<td>' + Number(hasilproduksi.finish_qty).toLocaleString("en-US") + '</td>';
        htmlContent += '<td>' + Number(hasilproduksi.target_qty).toLocaleString("en-US") + '</td>';
        htmlContent += '<td style="color:' + textColor + ';">' + Number(diffRekapHasilProduksi).toLocaleString("en-US") + '</td>';
        htmlContent += '</tr>';
    });
    
    $('#rekapHasilProduksi').html(htmlContent); 
}

function displayRekapHasilProduksiGrouping(hasilproduksi) {
    var htmlContent = '';
    $.each(hasilproduksi.hasilproduksi, function(index, hasilproduksi) {
        var diffRekapHasilProduksi = Number(hasilproduksi.target_qty)- Number(hasilproduksi.finish_qty)
        var textColor = diffRekapHasilProduksi < 0 ? 'red' : 'green';
        htmlContent += '<tr>';
        htmlContent += '<td>' + hasilproduksi.nama_line + '</td>';
        htmlContent += '<td>' + Number(hasilproduksi.finish_qty).toLocaleString("en-US") + '</td>';
        htmlContent += '<td>' + Number(hasilproduksi.target_qty).toLocaleString("en-US") + '</td>';
        htmlContent += '<td style="color:' + textColor + ';">' + Number(diffRekapHasilProduksi).toLocaleString("en-US") + '</td>';
        htmlContent += '</tr>';
    });
    
    $('#rekapHasilProduksiGrouping').html(htmlContent); 
}