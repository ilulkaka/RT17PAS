$(document).ready(function(){
    $('.select2').select2({
        theme: 'bootstrap4',
        // tags: true,
    });

    get_dataDashboard(key);
    get_success(null,null,key);
    //get_laporproduksi(key);
    allLine(key);
    
    get_info_update(key);
    $("#collapsePencapaianTarget").collapse("show");
    $("#col1").click(function() {
        if ($("#collapsePencapaianTarget").hasClass("show")) {
            // $(this).text('Click for Maximize');
            $("#collapsePencapaianTarget").collapse("hide");
        } else {
            // $(this).text('Click for Minimize');
            $("#collapsePencapaianTarget").collapse("show");
            $("#collapseHasilProduksi").collapse("hide");
        }
    });

    $("#collapseHasilProduksi").collapse("hide");
    $("#col2").click(function() {
        if ($("#collapseHasilProduksi").hasClass("show")) {
            // $(this).text('Click for Maximize');
            $("#collapseHasilProduksi").collapse("hide");
        } else {
            // $(this).text('Click for Minimize');
            $("#collapseHasilProduksi").collapse("show");
            $("#collapsePencapaianTarget").collapse("hide");
        }
    });

    $("#btn_close").click(function() {
        $.ajax({
                type: "GET",
                url: APP_BACKEND + "user/c_info_upd",
                dataType: "json",
                beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
            })
            .done(function(resp) {
                if (resp.success) {
                    $("#modal_info_update").modal('hide');
                } else {
                    $("#t_error").val(resp.message);
                }
            })
    });

     // Grafik Hasil Produksi
    var ctxHasil = document.getElementById('chart_hasilProduksi').getContext('2d');
    var chartHasil = new Chart(ctxHasil, {
        type: 'bar',
        data: {

        },
        options: {}
    });

    var tgl_1 = $("#tgl1").val();
    var tgl_2 = $("#tgl2").val();
    var select_line = $("#selectline").val();
    tampil_hasilProduksi(tgl_1, tgl_2, select_line, chartHasil, key);

    $("#btn_refreshHasil").click(function() {
        var tgl_1 = $("#tgl1").val();
        var tgl_2 = $("#tgl2").val();
        var select_line = $("#selectline").val();
        tampil_hasilProduksi(tgl_1, tgl_2, select_line, chartHasil, key);
    });

    $("#selectline").change(function() {
        var tgl_1 = $("#tgl1").val();
        var tgl_2 = $("#tgl2").val();
        var select_line = $("#selectline").val();
        tampil_hasilProduksi(tgl_1, tgl_2, select_line, chartHasil, key);
    })

     // Grafik Shikakari
    var ctx = document.getElementById('chart_shikakari').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {

        },
        options: {}
    });
    var awal = $("#tglAwal").val();
    tampil_c_shikakari(awal, chart, key);

    $("#btn_refresh").click(function() {
        var awal = $("#tglAwal").val();
        tampil_c_shikakari(awal, chart, key);
    });

    //grafik HH & KY
    var ctxhhky = document.getElementById('chartpie').getContext('2d');
    var charthhky = new Chart(ctxhhky, {
        type: 'bar',
        data: {},
        options: {}
    });
    get_hhky(charthhky,key);
   
    var ctxjam = document.getElementById('chartjam').getContext('2d');
    var chartjam = new Chart(ctxjam, {
        type: 'bar',
        data: {},
        options: {}
    });

    chart_jamkerusakan(chartjam, key);


});

function allLine(key){
    $.ajax({
        url: APP_BACKEND + "produksi/getline",
        type: "GET",
        dataType: 'json',
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
    }).done(function(res){
        var sel = select = document.getElementById('selectline');
            for(var p in res.data){
                var opt = document.createElement('option');
                    opt.value = res.data[p].kode_line;
                    opt.innerHTML = res.data[p].nama_line;
                    sel.appendChild(opt);
            }
        
    });
}
function get_dataDashboard(key){
    $("#hasil_produksi").html('Loading...');
    $("#hasil_sales").html('Loading...');
    $("#total_jam_lembur").html('Loading...');
    $('#marqueeExpres').html(`Loading...`);
    $.ajax({
        url: APP_BACKEND + "dashboard/get_dataDashboard",
        method: "GET",
        dataType: "json",
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },

        // =========== Hasil Produksi, Sales dan Lembur ==========
        success: function(resp) {
            let hasilProduksi = parseFloat(resp.acp9909);
            let hasilSales = parseFloat(resp.salespcs);
            let totalJamLembur = parseFloat(resp.lembur);

            if (isNaN(hasilProduksi)) {
                hasilProduksi = 0;
            }

            if (isNaN(hasilSales)) {
                hasilSales = 0;
            }

            if (isNaN(totalJamLembur)) {
                totalJamLembur = 0;
            }

            $("#hasil_produksi").html(hasilProduksi.toLocaleString() + ' Pcs');
            $("#hasil_sales").html(hasilSales.toLocaleString() + ' Pcs');
            $("#total_jam_lembur").html(totalJamLembur.toLocaleString() + ' Jam');

            // ================= Marque express =======================================

            let marqueeContent = '';

            if (resp.marqueexpres && resp.marqueexpres.length > 0) {
                $.each(resp.marqueexpres, function(index, item) {
                    let colorClass = '';
                    switch (item.warna_tag.toLowerCase()) {
                        case 'biru':
                            colorClass = 'warna-biru';
                            break;
                        case 'hijau':
                            colorClass = 'warna-hijau';
                            break;
                        case 'merah':
                            colorClass = 'warna-merah';
                            break;
                        case 'orange':
                            colorClass = 'warna-orange';
                            break;
                        case 'ungu':
                            colorClass = 'warna-ungu';
                            break;
                        case 'kuning':
                            colorClass = 'warna-kuning';
                            break;
                    }

                    marqueeContent += `<span class="marquee-item ${colorClass}">
                ${item.tag} ${item.warna_tag} ,<span style="color:black"> Target Finish ${item.finish} : <span style="color:red">${item.tot}</span> Lot
            </span></span>`;
                });
            } else {
                marqueeContent = '<span class="marquee-item">EXPRES : 0</span>';
            }

            // Menambahkan konten ke elemen marquee
            $('#marqueeExpres').html(`<b>${marqueeContent}</b>`);

            // ================= Mesin Stop =======================================
            if (resp.success) {
                var tbody = $('#tb_mesinoff tbody');
                tbody.empty();

                var mesinstop = resp.mesinstop;

                if (mesinstop.length > 0) {

                    $.each(mesinstop, function(index, item) {
                        var row = '<tr>' +
                            '<td>' + item.no_induk_mesin + '</td>' +
                            '<td>' + item.nama_mesin + '</td>' +
                            '<td>' + item.no_urut_mesin + '</td>' +
                            '<td>' + item.tanggal_rusak + '</td>' +
                            '<td>' + item.masalah + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="5">Tidak ada data yang tersedia.</td></tr>');
                }
            } else {
                console.error('Data tidak berhasil diambil:', resp);
            }



        },

        error: function(xhr, status, error) {
            console.error("Error: " + error);
        }
    })
}

function populateTable(data) {
    const headerRow = document.getElementById('table-header-row');
    const tbody = document.getElementById('data-table-body');
    tbody.innerHTML = '';

    const tgl_now = new Date().getDate();
    const thn_bln = new Date().toISOString().slice(0, 8);

    const formatter = new Intl.NumberFormat();

    // Clear previous headers
    while (headerRow.cells.length > 2) {
        headerRow.deleteCell(2);
    }

    // Populate headers for each day
    for (let i = 1; i <= tgl_now; i++) {
        const dateHeader = document.createElement('th');
        dateHeader.textContent = String(i).padStart(2, '0');
        headerRow.appendChild(dateHeader);
    }

    // Add Akumulasi, Monthly, and Diff headers
    const akumulasiHeader = document.createElement('th');
    // akumulasiHeader.id = 'total_finish_qty_header';
    akumulasiHeader.textContent = 'Akumulasi';
    headerRow.appendChild(akumulasiHeader);

    const monthlyHeader = document.createElement('th');
    monthlyHeader.textContent = 'Monthly';
    headerRow.appendChild(monthlyHeader);

    const diffHeader = document.createElement('th');
    diffHeader.textContent = 'Diff';
    headerRow.appendChild(diffHeader);

    Object.entries(data).forEach(([lineProses, lineData]) => {
        let row =
            `<tr>
            <td style="text-align: left"><b>${lineProses}</b></td>
            <td id="daily_target_${lineProses}" style="color: blue; font-weight: bold">${formatter.format(lineData['target_qty'][`${thn_bln}${String(tgl_now).padStart(2, '0')}`] ?? 0)}</td>`;

        for (let i = 1; i <= tgl_now; i++) {
            const dateStr = `${thn_bln}${String(i).padStart(2, '0')}`;

            // Pastikan tipe data numerik
            const finishQty = Number(lineData['finish_qty'][dateStr] ?? 0);
            const targetQty = Number(lineData['target_qty'][dateStr] ?? 0);

            // Perbaikan logika warna
            const textColor = finishQty < targetQty ?
                'red' :
                (finishQty > targetQty ?
                    'green' :
                    'black');

            row += `<td style="color: ${textColor}">${formatter.format(finishQty)}</td>`;
        }

        const akumulasi = lineData['akumulasi'];
        const targetMonth = lineData['target_month'];
        const diff = akumulasi - targetMonth;
        const diffColor = diff < 0 ? 'red' : (diff > 0 ? 'green' : 'black');

        row += `<td style="font-weight: bold">${formatter.format(akumulasi)}</td>
                <td style="color: blue; font-weight: bold">${formatter.format(targetMonth)}</td>
                <td style="color: ${diffColor}; font-weight: bold">${formatter.format(diff)}</td>
            </tr>`;

        tbody.insertAdjacentHTML('beforeend', row);
    });



    const select = document.getElementById('daily_target_select');
    select.innerHTML = '';
    for (let i = 1; i <= tgl_now; i++) {
        select.insertAdjacentHTML('beforeend',
            `<option value="${String(i).padStart(2, '0')}">${String(i).padStart(2, '0')}</option>`);
    }
}

function get_hhky(charthhky, key){

    $.ajax({
        url: APP_BACKEND + "hse/get_hhky/"+ new Date().getFullYear(),
        method: "GET",
        dataType: "json",
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
        success: function(response) {
            if (response.success) {
                const area = document.getElementById('list_hhky');
                var ky_in = 0;
                var ky_close = 0;
                var hh_in =0;
                var hh_close =0;
                for (var t in response.data){
                   ky_in = ky_in + Number(response.data[t].ky_masuk);
                   ky_close = ky_close + Number(response.data[t].ky_close);
                   hh_in = hh_in + Number(response.data[t].hh_masuk);
                   hh_close = hh_close + Number(response.data[t].hh_close);
                   
                }

                $("#hh_in").html(hh_in);
                $("#hh_close").html(hh_close);
                $("#ky_in").html(ky_in);
                $("#ky_close").html(ky_close);
                //TODO:grafik hhky
            }

            tampil_charthhky(charthhky,response.data);
            
        },
        error: function(error) {
            
        }
    });
}
function get_laporproduksi(key){
    $('#loadingLPP').show();
    $('#lpp').hide();
    $.ajax({
        url: APP_BACKEND + "dashboard/get_laporproduksi",
        method: "GET",
        dataType: "json",
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
        success: function(response) {
            $('#loadingLPP').hide();
            $('#lpp').show();
            localStorage.setItem('pivotTableData', JSON.stringify(response.pivotTable));
            populateTable(response.pivotTable);
        },
        error: function(error) {
            $('#loadingLPP').hide();
            $('#lpp').show();
            console.error('Error fetching data:', error);
        }
    });
}
function updateDailyTarget() {
            const selectedDate = document.getElementById('daily_target_select').value;
            const allData = JSON.parse(localStorage.getItem('pivotTableData'));

            const formatter = new Intl.NumberFormat();

            const currentYear = new Date().getFullYear();
            const currentMonth = String(new Date().getMonth() + 1).padStart(2, '0'); // Months are 0-indexed

            for (const [lineProses, data] of Object.entries(allData)) {
                const dailyTargetElement = document.getElementById(`daily_target_${lineProses}`);
                const targetQty = data['target_qty'][`${currentYear}-${currentMonth}-${selectedDate}`] ?? 0;

                dailyTargetElement.innerText = formatter.format(targetQty);
            };
 }

 function tampil_hasilProduksi(tgl_1, tgl_2, select_line, chartHasil, key) {
    $.ajax({
        url: APP_BACKEND + "produksi/g_hasilproduksi",
        method: "POST",
        dataType: "json",
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
        data: {
            "tgl_1": tgl_1,
            "tgl_2": tgl_2,
            "select_line": select_line,
        },
        success: function(data) {
            var tgl_proses = [];
            var line = [];
            var value = [];
            var lval = [];
            var target = [];
            var lpendek = [];
            var lsedang = [];
            var lpanjang = [];


            for (var i in data.g_hasilProduksi) {
                tgl_proses.push(data.g_hasilProduksi[i].tgl_proses);
                value.push(data.g_hasilProduksi[i].finish_qty);
                target.push(data.g_hasilProduksi[i].target_qty);
                lval.push(data.g_hasilProduksi[i].lot);
                lpendek.push(data.g_hasilProduksi[i].pendek);
                lsedang.push(data.g_hasilProduksi[i].sedang);
                lpanjang.push(data.g_hasilProduksi[i].panjang);
            }

            chartHasil.data = {
                labels: tgl_proses,
                datasets: [{
                        label: 'Target Produksi',
                        fill: false,
                        borderColor: 'red',
                        borderWidth: 2,
                        borderDash: [7, 7],
                        // borderColor: red 'rgb(255, 153, 51)',
                        pointBorderWidth: 5,
                        lineTension: 0.3,
                        data: target,
                        type: 'line'
                    },
                    {
                        label: 'Qty Hasil produksi',
                        backgroundColor: 'green',
                        borderColor: 'rgb(51, 153, 255)',
                        data: value,
                        // lval: lval,
                    },
                ]
            };

            chartHasil.options = {

                onClick: function(c, i) {
                    e = i[0];

                    var x_value = this.data.labels[e._index];
                    var y_value = this.data.datasets[0].data[e._index];
                }
            };

            chartHasil.options = {
                onClick: function(c, i) {},

                tooltips: {
                    callbacks: {
                        // title: function(tooltipItem, data) {
                        //     // Mengambil label dari tooltipItem
                        //     // return data['labels'][tooltipItem[0]['index']];

                        //     return data['labels'][tooltipItem[0]['index']] + ' : ' +
                        //         lval[
                        //             tooltipItem[0]['index']] + ' Lot';
                        // },
                        label: function(tooltipItem, data) {

                            // Fungsi untuk memastikan num adalah angka sebelum menggunakan toFixed()
                            function ensureNumber(num) {
                                if (typeof num === 'number') {
                                    return num;
                                } else {
                                    return parseFloat(
                                        num
                                    ); // Mengonversi num menjadi angka jika tidak sudah angka
                                }
                            }

                            // Fungsi untuk memformat angka dengan koma pada ribuan dan satu angka dibelakang koma
                            function formatNumber(num) {
                                return ensureNumber(num).toFixed(0).toString()
                                    .replace(
                                        /\B(?=(\d{3})+(?!\d))/g, ",");
                            }

                            if (tooltipItem.datasetIndex === 0) {
                                // Mendapatkan nilai dari dataset 'Target Shikakari' (index 0)
                                var targetValue = data['datasets'][0]['data'][
                                    tooltipItem[
                                        'index']
                                ];
                                return 'Target Produksi : ' + formatNumber(
                                        targetValue) +
                                    ' Pcs';
                            } else if (tooltipItem.datasetIndex === 1) {
                                var qtyValue = data['datasets'][1]['data'][
                                    tooltipItem[
                                        'index']
                                ];
                                var lot = lval[tooltipItem.index];
                                var pendek = lpendek[tooltipItem.index];
                                var sedang = lsedang[tooltipItem.index];
                                var panjang = lpanjang[tooltipItem.index];
                                return ['Qty Finish : ' + formatNumber(
                                        qtyValue) +
                                    ' Pcs',
                                    'Rata-rata : ' +
                                    formatNumber(qtyValue / lot) + '  Pcs/Lot',
                                    '------------------------------------',
                                    'Hasil Lot       : ' + lot,
                                    '------------------------------------',
                                    'Pendek     : ' + pendek,
                                    'Sedang     : ' + sedang,
                                    'Panjang    : ' + panjang,
                                ];
                            }
                        }
                    },
                    backgroundColor: '#000033',
                    titleFontSize: 16,
                    titleFontColor: '#FFFFFF',
                    bodyFontColor: '#FFFFFF',
                    bodyFontSize: 14,
                    displayColors: false // Menyembunyikan warna di tooltip
                }
            };
            chartHasil.update();
        }
    });
}

 function tampil_c_shikakari(awal, chart, key) {
    $('#loadingMessage').show();
    $('#chart_shikakari').hide();
    $.ajax({
        url: APP_BACKEND + "produksi/g_shikakari",
        method: "GET",
        dataType: "json",
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
        success: function(data) {
            var line = [];
            var value = [];
            var lval = [];
            var target = [];
            var lpendek = [];
            var lsedang = [];
            var lpanjang = [];


            for (var i in data.g_shikakari) {
                line.push(data.g_shikakari[i].nama_line);
                value.push(data.g_shikakari[i].qty);
                lval.push(data.g_shikakari[i].lot);
                target.push(data.g_shikakari[i].target);
                lpendek.push(data.g_shikakari[i].pendek);
                lsedang.push(data.g_shikakari[i].sedang);
                lpanjang.push(data.g_shikakari[i].panjang);
            }

            chart.data = {
                labels: line,
                datasets: [{
                        label: 'Target Shikakari',
                        fill: false,
                        yAxisID: 'B',
                        borderColor: 'red',
                        borderWidth: 2,
                        borderDash: [7, 7],
                        // borderColor: red 'rgb(255, 153, 51)',
                        pointBorderWidth: 5,
                        lineTension: 0.3,
                        data: target,
                        type: 'line'
                    },
                    {
                        label: 'Qty Shikakari',
                        yAxisID: 'A',
                        backgroundColor: 'rgb(51, 153, 255)',
                        borderColor: 'rgb(51, 153, 255)',
                        data: value,
                        lval: lval,
                    },
                ]
            };

            chart.options = {
                onClick: function(c, i) {},
                scales: {
                    B: {
                            beginAtZero: true,
                            position: 'right',
                            type: 'linear'
                        },
                    A:{
                        beginAtZero: true,
                        position: 'left',
                        type: 'linear'
                    }
                     
                },
                plugins:{
                    tooltip: {
                        callbacks: {
                           
                            label: function(context) {
                                
                                // Fungsi untuk memastikan num adalah angka sebelum menggunakan toFixed()
                                function ensureNumber(num) {
                                    if (typeof num === 'number') {
                                        return num;
                                    } else {
                                        return parseFloat(
                                            num
                                        ); // Mengonversi num menjadi angka jika tidak sudah angka
                                    }
                                }

                                // Fungsi untuk memformat angka dengan koma pada ribuan dan satu angka dibelakang koma
                                function formatNumber(num) {
                                    return ensureNumber(num).toFixed(0).toString()
                                        .replace(
                                            /\B(?=(\d{3})+(?!\d))/g, ",");
                                }

                                if (context.datasetIndex === 0) {
                                    // Mendapatkan nilai dari dataset 'Target Shikakari' (index 0)
                                    var targetValue = context.dataset.data[context.dataIndex];
                                    return 'Target Shikakari : ' + formatNumber(
                                            targetValue) +
                                        ' Pcs';
                                } else if (context.datasetIndex === 1) {
                                    var qtyValue = context.dataset.data[context.dataIndex];
                                    var lot = context.dataset.lval[context.dataIndex];
                                    var pendek = lpendek[context.dataIndex];
                                    var sedang = lsedang[context.dataIndex];
                                    var panjang = lpanjang[context.dataIndex];
                                    return ['Qty Shikakari : ' + formatNumber(
                                            qtyValue) +
                                        ' Pcs',
                                        'Rata-rata   : ' + formatNumber(qtyValue /
                                            lot) + '  Pcs/Lot',
                                        '------------------------------------',
                                        'Total Lot       : ' + lot,
                                        '------------------------------------',
                                        'Lot Pendek  : ' + pendek,
                                        'Lot Sedang  : ' + sedang,
                                        'Lot Panjang : ' + panjang,
                                    ];
                                }
                            }
                        },
                        backgroundColor: '#000033',
                        titleFontSize: 16,
                        titleFontColor: '#FFFFFF',
                        bodyFontColor: '#FFFFFF',
                        bodyFontSize: 14,
                        displayColors: false // Menyembunyikan warna di tooltip
                    }
                },
                
            };
            chart.update();

            $('#loadingMessage').hide();
            $('#chart_shikakari').show();
        },

        error: function() {
            // Menyembunyikan pesan "Loading..." dan menampilkan canvas chart
            $('#loadingMessage').hide();
            $('#chart_shikakari').show();
        }
    });
}

function tampil_charthhky(charthhky, data) {


        var label = [];
        var value = [];
        var value1 = [];
        var hhclose = [];
        var kyclose = [];
        var coloR = [];
        var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        };

        for (var i in data) {
            label.push(data[i].bagian);

            value.push(Number(data[i].hh_masuk)-Number(data[i].hh_close));
            value1.push(Number(data[i].ky_masuk)-Number(data[i].ky_close));
            hhclose.push(data[i].hh_close);
            kyclose.push(data[i].ky_close);
            coloR.push(dynamicColors());
        }

        charthhky.data = {
            labels: label,
            datasets: [{
                    label: 'HH Open',
                    yAxisID: 'A',
                    lbl: 'HH',
                    fill: true,
                    borderColor: 'rgb(46, 139, 87)',
                    backgroundColor: 'rgb(255,0,127)',
                    data: value,
                    type: 'bar'



                },
                {
                    label: 'KY Open',
                    yAxisID: 'B',
                    lbl: 'KY',
                    fill: true,
                    backgroundColor: 'rgb(51, 153, 255)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: value1,
                    type: 'bar'
                }
            ]
        };
        charthhky.options = {
            scales: {
                B: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                    },

                
            },
            onClick: function(e, i) {
                c = i[0];
                //alert(e);
                const activePoints = charthhky.getElementsAtEventForMode(e, 'nearest', {
                    intersect: true
                }, false)
                var indexNo = activePoints[0]._datasetIndex;
                var x_value = this.data.datasets[indexNo].lbl;


                var y_value = this.data.labels[c._index];
                //console.log(y_value);
                //var x_value1 = this.data.datasets[0].lbl[index];

                $('#lbl_hhky').html(x_value + '_');
                $('#lbl_hhky1').html(y_value);


                get_lokasi_hhky(x_value, y_value, key);
                $('#modal-hhky').modal('show');
            },
        };
        charthhky.update();
    


}

function chart_jamkerusakan(chart, key) {
  var d = new Date();
  var bln = d.getMonth() + 1;
    $.ajax({
        url: APP_BACKEND + "maintenance/grafikjam/"+ d.getFullYear()+"-"+bln,
        method: "GET",
        dataType: "json",
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
        success: function(data) {
            var label = [];
            var value = [];
            var totaljam = 0;
            for (var i in data) {
                label.push(data[i].departemen);
                value.push(data[i].jam);
                totaljam = totaljam + Number(data[i].jam);
            }

            chart.data = {
                labels: label,
                datasets: [{
                        label: 'Jam Kerusakan',
                        backgroundColor: 'rgb(51, 153, 255)',
                        borderColor: 'rgb(51, 153, 255)',
                        data: value,
                    }

                ]
            };
            chart.options = {

                onClick: function(c, i) {
                    e = i[0];
                    console.log(this.data.labels);
                    
                    var x_value = this.data.labels[0];
                    //var tgl = $("#tgl2").val();
                    var tgl = d.getFullYear()+"-"+bln;
                    var p = {
                        dept: x_value,
                        period: tgl
                    };
                    action_data(APP_BACKEND + "maintenance/detailjam","POST", p, key)
                        .done(function(resp) {
                            $("#tb_detail tbody").empty();

                            for (var i in resp) {
                                var newrow = '<tr><td>' + resp[i]
                                    .no_induk_mesin + '</td><td>' + resp[i]
                                    .nama_mesin + '</td><td>' + Number(resp[i]
                                        .jam_rusak).toFixed(2) +
                                    ' Jam</td></tr>';
                                $('#tb_detail tbody').append(newrow);
                            }
                        });
                    //get_details(x_value,tgl, key);
                    $("#labeldetail").html("TOP 5 Kerusakan " + x_value);
                    $("#modal-detail").modal("toggle");
                }
            };
            chart.update();
            $("#totaljam").html("Total Jam Kerusakan : " + totaljam.toFixed(2) + " Jam");
        }

    });



}

function get_success(tgl1, tgl2, key) {
    $("#success-rate").html('Loading...');
    $.ajax({
            url: APP_BACKEND + "dashboard/successrate",
            method: "POST",
            data: {
                "tgl_awal": tgl1,
                "tgl_akhir": tgl2
            },
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
        })
        .done(function(resp) {
            if (resp.success) {
                $("#success-rate").html(resp.persen + ' %');
            }
        })
}


function action_data(url,met, datas, key) {
    return $.ajax({
        url: url,
        type: met,
        dataType: 'json',
        beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
        data: datas,
    });
}

 function get_info_update(key){
    $.ajax({
            url: APP_BACKEND + "user/n_info_upd",
            method: "GET",
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization","Bearer " + key);
             },
        })
        .done(function(resp) {
            if (resp.success) {
                $("#n_deskripsi").val(resp.deskripsi);
                $("#modal_info_update").modal('show');
            }
        })
 }